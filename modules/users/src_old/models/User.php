<?php
namespace app\modules\users\models;

use app\modules\users\Module;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password password
 * @property string $password2 repeated password
 * @property Profile $profile Profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 3;
    const ROLE_USER = 10;

    const ROLE_DEFAULT = 'user';

    /**
     * @var string|null Password
     */
    public $password;

    /**
     * @var string|null Repeated password
     */
    public $password2;

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
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['username', 'email'], 'required'],
            [['password', 'password2'], 'required', 'on' => ['create']],

            // Trim
            [['username', 'email', 'password', 'password2'], 'trim'],

            // Unique
            [['username', 'email'], 'unique'],

            // E-mail
            ['email', 'email'],

            // Password
            ['password2', 'compare', 'compareAttribute' => 'password', 'on' => ['create']],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],

            ['role', 'default', 'value' => self::ROLE_DEFAULT],
            ['role', 'in', 'range' => [self::ROLE_DEFAULT]],
        ];
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
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
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
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('users', 'Username'),
            'email' => Module::t('users', 'E-mail'),
            'role' => Module::t('users', 'Role'),
            'status' => Module::t('users', 'Status'),
            'created_at' => Module::t('users', 'Created at'),
            'updated_at' => Module::t('users', 'Updated at'),
            'password' => Module::t('users', 'Password'),
            'password2' => Module::t('users', 'Repeated password')
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
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
        return $this->hasOne(Profile::className(), ['user_id' => 'id'])->inverseOf('user');
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
     * @return array Roles list
     */
    public static function getRolesList()
    {
        return Yii::$app->authManager ? ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description') : array();
    }
}
