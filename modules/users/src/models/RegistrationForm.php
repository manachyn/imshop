<?php

namespace im\users\models;

use yii\base\Model;
use yii\web\User;

/**
 * Class RegistrationForm collects user input on registration process, validates it and creates new User model.
 *
 * @package im\users\models
 */
class RegistrationForm extends Model
{
    /** @var string */
    public $email;

    /** @var string */
    public $username;

    /** @var string */
    public $password;

    /** @var User */
    public $user;
//
//    /** @var Module */
//    protected $module;

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        $this->user = \Yii::createObject([
//            'class'    => User::className(),
//            'scenario' => 'register'
//        ]);
//        $this->module = \Yii::$app->getModule('user');
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'    => \Yii::t('user', 'Email'),
            'username' => \Yii::t('user', 'Username'),
            'password' => \Yii::t('user', 'Password'),
        ];
    }
}