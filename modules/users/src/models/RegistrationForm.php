<?php

namespace im\users\models;

use im\users\Module;
use Yii;
use yii\base\Model;

/**
 * Class RegistrationForm collects user input on registration process, validates it and creates new User model.
 *
 * @package im\users\models
 */
class RegistrationForm extends Model
{
    /**
     * @var string username
     */
    public $username;

    /**
     * @var string email
     */
    public $email;

    /**
     * @var string password
     */
    public $password;

    /**
     * @var string repeated password
     */
    public $password2;

    /**
     * @var string first name
     */
    public $firstName;

    /**
     * @var string last name
     */
    public $lastName;

    /**
     * @var Module module instance
     */
    protected $module;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $module = $this->getModule();
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => $module->userModel/*, 'message' => 'This username has already been taken.'*/],
            ['username', 'string', 'min' => 2, 'max' => 100],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' =>  $module->userModel/*, 'message' => 'This email address has already been taken.'*/],

            ['password', 'required', 'skipOnEmpty' => $module->passwordAutoGenerating],
            ['password', 'string', 'min' => 6],

            ['password2', 'required', 'skipOnEmpty' => $module->passwordAutoGenerating],
            ['password2', 'compare', 'compareAttribute' => 'password'],

            [['firstName', 'lastName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('registration', 'Username'),
            'email' => Module::t('registration', 'E-mail'),
            'password' => Module::t('registration', 'Password'),
            'password2' => Module::t('registration', 'Repeated password'),
            'firstName' => Module::t('registration', 'First name'),
            'lastName' => Module::t('registration', 'Last name'),
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
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function register()
    {
        if ($this->validate()) {

            /** @var User $userClass */
            $userClass = $this->module->userModel;

            /** @var User $user */
            $user = Yii::$container->get($userClass, [], ['scenario' => $userClass::SCENARIO_REGISTER]);

            /** @var Profile $profileClass */
            $profileClass = $this->module->profileModel;

            /** @var Profile $profile */
            $profile = Yii::$container->get($profileClass, [], ['scenario' => $profileClass::SCENARIO_REGISTER]);

            $user->setAttributes([
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password
            ]);

            $profile->setAttributes([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName
            ]);

            $user->profile = $profile;

            if ($user->register()) {
                return $user;
            }
        }

        return null;
    }
}