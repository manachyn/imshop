<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Class ProfileForm
 * @package im\users\models
 */
class ProfileForm extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string repeated password
     */
    public $password2;

    /**
     * @var string
     */
    public $currentPassword;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var User
     */
    private $_user;

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $user = $this->getUser();
        $this->username = $user->username;
        $this->email = $user->email;
        $this->firstName = $user->profile->first_name;
        $this->lastName = $user->profile->last_name;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $module = $this->getModule();
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => $module->userModel],
            ['username', 'string', 'min' => 2, 'max' => 100],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' =>  $module->userModel],

            ['password', 'string', 'min' => 6],
            ['password2', 'compare', 'compareAttribute' => 'password'],
            ['currentPassword', 'required', 'when' => function () {
                return $this->password !== null;
            }],
            ['currentPassword', 'validateCurrentPassword'],

            [['firstName', 'lastName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('profile', 'Username'),
            'email' => Module::t('profile', 'E-mail'),
            'password' => Module::t('profile', 'Password'),
            'password2' => Module::t('profile', 'Repeated password'),
            'current_password' => Module::t('profile', 'Current password'),
            'firstName' => Module::t('profile', 'First name'),
            'lastName' => Module::t('profile', 'Last name'),
        ];
    }

    /**
     * Validates current password.
     *
     * @param string $attribute
     */
    public function validateCurrentPassword($attribute)
    {
        $user = $this->getUser();
        if ($this->password && (!$user || !$user->validatePassword($this->$attribute))) {
            $this->addError($attribute, Module::t('profile', 'Current password is not valid'));
        }
    }
}
