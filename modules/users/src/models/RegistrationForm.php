<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Class RegistrationForm collects user input on registration process, validates it.
 * It is used for creation new 'User' model.
 *
 * @package im\users\models
 */
class RegistrationForm extends Model
{
    use ModuleTrait;

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
     * @inheritdoc
     */
    public function rules()
    {
        $module = $this->getModule();
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 100],
            ['username', 'unique', 'targetClass' => $module->userModel/*, 'message' => 'This username has already been taken.'*/],

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
}