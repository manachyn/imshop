<?php

namespace im\users\models;

use im\users\Module;
use yii\base\Model;

/**
 * Class PasswordResetForm collects data for password resetting.
 *
 * @package im\users\models
 */
class PasswordResetForm extends Model
{
    /**
     * @var string password
     */
    public $password;

    /**
     * @var string repeated password
     */
    public $password2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password2', 'required'],
            ['password2', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Module::t('recovery', 'Password'),
            'password2' => Module::t('recovery', 'Repeated password')
        ];
    }
}