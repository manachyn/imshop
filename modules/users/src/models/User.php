<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use Yii;

/**
 * User model.
 *
 * @property integer $id ID
 * @property string $username Username
 * @property string $password_hash Password hash
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
    use ModuleTrait;

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
     * @var string the name of the update scenario.
     */
    const SCENARIO_UPDATE = 'update';

    /**
     * @var string the name of the connect scenario.
     */
    const SCENARIO_CONNECT = 'connect';

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
     * @var string|null Password
     */
    public $password;

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
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique'],
            ['username', 'string', 'min' => 2, 'max' => 100],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],

            ['password', 'required', 'on' => [static::SCENARIO_REGISTER]],
            ['password', 'string', 'min' => 6],

            ['status', 'default', 'value' => $this->module->registrationConfirmation ? static::STATUS_INACTIVE : static::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(static::getStatusesList())],

            ['role', 'default', 'value' => static::ROLE_DEFAULT],
            ['role', 'in', 'range' => [static::ROLE_DEFAULT]],

            [['registration_ip', 'last_login_ip', 'created_at', 'updated_at', 'confirmed_at', 'last_login_at', 'blocked_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username', 'password'],
            self::SCENARIO_REGISTER => ['username', 'email', 'password'],
            self::SCENARIO_CONNECT => ['username', 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('user', 'Username'),
            'password_hash' => Module::t('user', 'Password hash'),
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
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => static::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username.
     *
     * @param string $username
     * @param int $status
     * @return static|null
     */
    public static function findByUsername($username, $status = null)
    {
        $condition = ['username' => $username];
        if ($status) {
            $condition['status'] = $status;
        }

        return static::findOne($condition);
    }

    /**
     * Finds user by email.
     *
     * @param string $email
     * @param int $status
     * @return static|null
     */
    public static function findByEmail($email, $status = null)
    {
        $condition = ['email' => $email];
        if ($status) {
            $condition['status'] = $status;
        }

        return static::findOne($condition);
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
     * Validates password.
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function setAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
                $this->setAuthKey();
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
//        $name = $this->role ? $this->role : static::ROLE_DEFAULT;
//        $role = $auth->getRole($name);
//
//        if (!$insert) {
//            $auth->revokeAll($this->id);
//        }
//
//        $auth->assign($role, $this->id);
    }

    /**
     * Returns profile relation.
     *
     * @return ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->getModule()->profileModel, ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Returns readable status.
     *
     * @return string
     */
    public function getStatus()
    {
        $statuses = static::getStatusesList();

        return $statuses[$this->status];
    }

    /**
     * Return statuses list.
     *
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            static::STATUS_INACTIVE => Module::t('users', 'Inactive'),
            static::STATUS_ACTIVE => Module::t('users', 'Active'),
            static::STATUS_DELETED => Module::t('users', 'Deleted')
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
     * @return array
     */
    public static function getRolesList()
    {
        return Yii::$app->authManager ? ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description') : array();
    }

    /**
     * Check user confirmation status.
     *
     * @return bool whether the user is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    /**
     * Check user active status.
     *
     * @return bool whether the user is activated.
     */
    public function isActive()
    {
        return $this->status === static::STATUS_ACTIVE;
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
}
