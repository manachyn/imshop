<?php

namespace vova07\users\models\backend;

use vova07\users\Module;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class User
 * @package vova07\users\models\backend
 * User administrator model.
 *
 * @property string|null $password Password
 * @property string|null $repassword Repeat password
 *
 * @property Profile $profile Profile
 */
class User extends \vova07\users\models\User
{
    /**
     * @var string|null Password
     */
    public $password;

    /**
     * @var string|null Repeat password
     */
    public $repassword;

    /**
     * @var string Model status.
     */
    private $_status;

    /**
     * @return string Model status.
     */
    public function getStatus()
    {
        if ($this->_status === null) {
            $statuses = self::getStatusArray();
            $this->_status = $statuses[$this->status_id];
        }
        return $this->_status;
    }

    /**
     * @return array Status array.
     */
    public static function getStatusArray()
    {
        return [
            self::STATUS_ACTIVE => Module::t('users', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Module::t('users', 'STATUS_INACTIVE'),
            self::STATUS_BANNED => Module::t('users', 'STATUS_BANNED')
        ];
    }

    /**
     * @return array Role array.
     */
    public static function getRoleArray()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['username', 'email'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['admin-create']],
            // Trim
            [['username', 'email', 'password', 'repassword', 'name', 'surname'], 'trim'],
            // String
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email'], 'unique'],
            // Username
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            ['email', 'string', 'max' => 100],
            ['email', 'email'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            // Role
            ['role', 'in', 'range' => array_keys(self::getRoleArray())],
            // Status
            ['status_id', 'in', 'range' => array_keys(self::getStatusArray())]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'admin-create' => ['username', 'email', 'password', 'repassword', 'status_id', 'role'],
            'admin-update' => ['username', 'email', 'password', 'repassword', 'status_id', 'role']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'password' => Module::t('users', 'ATTR_PASSWORD'),
                'repassword' => Module::t('users', 'ATTR_REPASSWORD')
            ]
        );
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
                $this->generateToken();
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

        if ($this->profile !== null) {
            $this->profile->save(false);
        }
    }
}
