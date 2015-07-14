<?php

namespace im\users\models;

use im\users\components\ProfileInterface;
use im\users\components\UserInterface;
use im\users\traits\ModuleTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
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
class UserNew extends ActiveRecord implements IdentityInterface, UserInterface
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
     * @return ActiveQuery
     */
    public function getProfileRelation()
    {
        return $this->hasOne($this->module->profileModel, ['user_id' => 'id'])->inverseOf('user');
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
     * @inheritdoc
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @inheritdoc
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @inheritdoc
     */
    public function setAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public function setPasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @inheritdoc
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function setProfile(ProfileInterface $profile)
    {
        $this->populateRelation('profile', $profile);
    }

    /**
     * @inheritdoc
     */
    public function getProfile()
    {
        return $this->getProfileRelation()->one();
    }

    /**
     * @inheritdoc
     */
    public function isConfirmed()
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    /**
     * @inheritdoc
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt->getTimestamp();
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->created_at);

        return $time;
    }

    /**
     * @inheritdoc
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt->getTimestamp();
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->updated_at);

        return $time;
    }

    /**
     * @inheritdoc
     */
    public function setLastLoginAt(\DateTime $lastLoginAt)
    {
        $this->last_login_at = $lastLoginAt->getTimestamp();
    }

    /**
     * @inheritdoc
     */
    public function getLastLoginAt()
    {
        $time = new \DateTime();
        $time->setTimestamp($this->last_login_at);

        return $time;
    }

    /**
     * @inheritdoc
     */
    public function setRegistrationIp($registrationIp)
    {
        $this->registration_ip = ip2long($registrationIp);
    }

    /**
     * @inheritdoc
     */
    public function getRegistrationIp()
    {
        return long2ip($this->registration_ip);
    }

    /**
     * @inheritdoc
     */
    public function setLastLoginIp($lastLoginIp)
    {
        $this->last_login_ip = ip2long($lastLoginIp);
    }

    /**
     * @inheritdoc
     */
    public function getLastLoginIp()
    {
        return long2ip($this->last_login_ip);
    }
}
