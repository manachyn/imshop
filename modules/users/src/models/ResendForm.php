<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

/**
 * Class ResendForm is a model which collects user's email or username, validate it's confirmation status.
 * It is used to send new confirmation token to the user.
 *
 * @package im\users\models
 */
class ResendForm extends Model
{
    use ModuleTrait;

    /**
     * @var string username or email
     */
    public $email;

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
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'string', 'max' => 255],
            ['email', 'validateEmail']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('registration', 'E-mail or username'),
        ];
    }

    /**
     * Validates email.
     */
    public function validateEmail()
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError('email', Module::t('registration', 'User with such email or username is not found'));
        } elseif ($user->isConfirmed()) {
            $this->addError('email', Module::t('registration', 'This account has already been confirmed.'));
        }
    }

    /**
     * Get user by username or email.
     *
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === null) {
            /** @var User $userClass */
            $userClass = $this->module->userModel;
            $this->_user = $userClass::findOne(['email' => $this->email]) ?: $userClass::findOne(['username' => $this->email]);
        }

        return $this->_user;
    }
}