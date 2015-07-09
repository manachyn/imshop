<?php
namespace im\users\models;

use im\users\Module;
use Yii;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model.
 *
 * @property integer $id ID
 * @property string $username Username
 * @property string $password_hash Password hash
 * @property string $password_reset_token Password reset token
 * @property string $email E-mail
 * @property string $auth_key Authentication key
 * @property string $access_token Access token
 * @property integer $role Role
 * @property integer $status Status
 * @property string $registration_ip Registration IP
 * @property string $last_login_ip Last login IP
 * @property integer $created_at Created time
 * @property integer $updated_at Updated time
 * @property integer $confirmed_at Confirmed time
 * @property integer $last_login_at Last login time
 * @property integer $blocked_at Blocked time
 *
 * @property Profile $profile Profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @var string the name of the login scenario.
     */
    const SCENARIO_LOGIN = 'login';

    /**
     * @var string the name of the register scenario.
     */
    const SCENARIO_REGISTER = 'register';

    /**
     * @var string the name of the create scenario.
     */
    const SCENARIO_CREATE = 'create';

    /**
     * @var int inactive status
     */
    const STATUS_INACTIVE = 0;

    /**
     * @var int active status
     */
    const STATUS_ACTIVE = 1;

    /**
     * @var int blocked status
     */
    const STATUS_BLOCKED = 2;

    /**
     * @var int deleted status
     */
    const STATUS_DELETED = 3;

    const ROLE_USER = 10;

    const ROLE_DEFAULT = 'user';

    /**
     * @event ModelEvent an event that is triggered before user registration.
     */
    const EVENT_BEFORE_REGISTRATION = 'beforeRegistration';

    /**
     * @event Event an event that is triggered after a user is registered.
     */
    const EVENT_AFTER_REGISTRATION = 'afterRegistration';

    /**
     * @var string|null Password
     */
    public $password;

    /**
     * @var string|null Repeated password
     */
    public $password2;

    /**
     * @var Module module instance
     */
    protected $module;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $module = $this->getModule();
        return [
            // Required
            [['username', 'email'], 'required'],
            [['password', 'password2'], 'required', 'on' => [static::SCENARIO_REGISTER], 'skipOnEmpty' => $module->passwordAutoGenerating],

            // Trim
            [['username', 'email', 'password', 'password2'], 'trim'],

            // Unique
            [['username', 'email'], 'unique'],

            [['username'], 'string', 'max' => 100],

            // E-mail
            ['email', 'email'],

            // Password
            ['password2', 'compare', 'compareAttribute' => 'password', 'on' => [static::SCENARIO_REGISTER]],

            ['status', 'default', 'value' => $module->registrationConfirmation ? self::STATUS_INACTIVE : self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],

            ['role', 'default', 'value' => self::ROLE_DEFAULT],
            ['role', 'in', 'range' => [self::ROLE_DEFAULT]],
        ];
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module ?: $this->module = Yii::$app->getModule('users');
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Confirms the user.
     */
    public function confirm()
    {
        $this->status = static::STATUS_ACTIVE;
        $this->blocked_at = time();
    }

    /**
     * Blocks the user.
     */
    public function block()
    {
        $this->status = static::STATUS_BLOCKED;
        $this->blocked_at = time();
    }

    /**
     * Unblocks the user.
     */
    public function unblock()
    {
        $this->status = static::STATUS_ACTIVE;
        $this->blocked_at = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'Username'),
            'password_hash' => Module::t('user', 'Password hash'),
            'password_reset_token' => Module::t('user', 'Password reset token'),
            'email' => Module::t('user', 'E-mail'),
            'auth_key' => Module::t('user', 'Authentication key'),
            'access_token' => Module::t('user', 'Access token'),
            'role' => Module::t('user', 'Role'),
            'status' => Module::t('user', 'Status'),
            'registration_ip' => Module::t('user', 'Registration IP'),
            'last_login_ip' => Module::t('user', 'Last login IP'),
            'created_at' => Module::t('user', 'Created at'),
            'updated_at' => Module::t('user', 'Updated at'),
            'confirmed_at' => Module::t('user', 'Confirmed at'),
            'last_login_at' => Module::t('user', 'Last login at'),
            'blocked_at' => Module::t('user', 'Blocked at'),
            'password' => Module::t('user', 'Password'),
            'password2' => Module::t('user', 'Repeated password')
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $module = $this->getModule();
            if ($this->isNewRecord && !$this->password && $module->passwordAutoGenerating) {
                $this->password = Yii::$app->security->generateRandomString($module->passwordLength);
            }
            if ($this->isNewRecord || $this->password) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

//        if ($this->profile !== null) {
//            $this->profile->save(false);
//        }

//        $auth = Yii::$app->authManager;
//        $name = $this->role ? $this->role : self::ROLE_DEFAULT;
//        $role = $auth->getRole($name);
//
//        if (!$insert) {
//            $auth->revokeAll($this->id);
//        }
//
//        $auth->assign($role, $this->id);
    }

    /**
     * @return ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->getModule()->profileModel, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return string Readable status
     */
    public function getStatus()
    {
        $statuses = self::getStatusesList();

        return $statuses[$this->status];
    }

    /**
     * Return statuses list.
     *
     * @return array Statuses list
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_INACTIVE => Module::t('users', 'Inactive'),
            self::STATUS_ACTIVE => Module::t('users', 'Active'),
            self::STATUS_DELETED => Module::t('users', 'Deleted')
        ];
    }

    /**
     * Set user profile.
     *
     * @param Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->populateRelation('profile', $profile);
    }

    /**
     * Return roles list.
     *
     * @return array Roles list
     */
    public static function getRolesList()
    {
        return Yii::$app->authManager ? ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description') : array();
    }

    /**
     * Log in a user.
     *
     * @return bool whether the user is logged in.
     */
    public function login()
    {
        return Yii::$app->user->login($this);
    }

    /**
     * Register a user.
     *
     * @return bool whether the user is registered.
     */
    public function register()
    {
        if (!$this->beforeRegister(false)) {
            return false;
        }

        $this->registration_ip = ip2long(Yii::$app->request->userIP);

        if ($this->save() && $this->profile->save()) {
            $this->link('profile', $this->profile);
            $this->afterRegister();
            return true;
        } else {
            return false;
        }
    }

    /**
     * This method is called at the beginning of user registration.
     * The default implementation will trigger an [[EVENT_BEFORE_REGISTRATION]] event.
     *
     * @return boolean whether the registration should continue.
     */
    public function beforeRegister()
    {
        $event = new ModelEvent;
        $this->trigger(self::EVENT_BEFORE_REGISTRATION, $event);

        return $event->isValid;
    }

    /**
     * This method is called at the end of user registration.
     * The default implementation will trigger an [[EVENT_AFTER_REGISTRATION]] event.
     */
    public function afterRegister()
    {
        $event = new Event;
        $this->trigger(self::EVENT_AFTER_REGISTRATION, $event);
    }
}
