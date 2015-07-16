<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use yii\base\Model;

/**
 * Class RecoveryForm collects data for password recovery.
 *
 * @package im\users\models
 */
class RecoveryForm extends Model
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
            'email' => Module::t('recovery', 'E-mail or username'),
        ];
    }

    /**
     * Validates email.
     */
    public function validateEmail()
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError('email', Module::t('recovery', 'User with such email or username is not found.'));
        } elseif (!$user->isConfirmed()) {
            $this->addError('email', Module::t('recovery', 'This account in not confirmed.'));
        } elseif (!$user->isActive()) {
            $this->addError('email', Module::t('recovery', 'This account in not activated.'));
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
            $this->_user = $userClass::findOne(['email' => $this->email]) ?: $userClass::findOne(['username' => $this->email]);
        }

        return $this->_user;
    }
}