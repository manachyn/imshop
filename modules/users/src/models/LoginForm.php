<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use yii\base\Model;

class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string email or username.
     */
    public $username;

    /**
     * @var string password.
     */
    public $password;

    /**
     * @var bool whether to remember the user.
     */
    public $rememberMe = true;

    /**
     * @var User user instance
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['password', 'required'],
            ['password', 'validatePassword'],
            ['username', 'validateAccount'],
            ['rememberMe', 'boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('login', 'Username'),
            'password' => Module::t('login', 'Password'),
            'rememberMe' => Module::t('login', 'Remember me')
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Validates the account status.
     */
    public function validateAccount()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                if (!$user->isConfirmed()) {
                    $this->addError('username', Module::t('login', 'This account in not confirmed.'));
                } elseif (!$user->isActive()) {
                    $this->addError('username', Module::t('login', 'This account in not activated.'));
                }
            }
        }
    }

    /**
     * Finds user by username or email.
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            /** @var User $userClass */
            $userClass = $this->module->userModel;
            $this->_user = $userClass::findByUsername($this->username) ?: $userClass::findByEmail($this->username);
        }

        return $this->_user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }
}