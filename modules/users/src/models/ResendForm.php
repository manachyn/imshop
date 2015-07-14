<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;

class ResendForm extends Model
{
    use ModuleTrait;

    /**
     * @var string username or email
     */
    public $email;

    /**
     * @var User
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
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', function ($attribute) {
                if ($this->getUser()->isConfirmed()) {
                    $this->addError($attribute, Module::t('registration', 'This account has already been confirmed.'));
                }
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Module::t('registration', 'E-mail'),
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
            $userClass = $this->getModule()->userModel;
            $this->_user = $userClass::findOne(['email' => $this->email]) || $userClass::findOne(['username' => $this->email]);
        }

        return $this->_user;
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @return bool
     */
    public function resend()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        /** @var Token $tokenClass */
        $tokenClass = $this->getModule()->tokenModel;
        $token = $tokenClass::generate($user->getId(), $tokenClass::TYPE_REGISTRATION_CONFIRMATION);
        $this->mailer->sendRegistrationConfirmationEmail($user, $token);

        \Yii::$app->session->setFlash('info', \Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));

        return true;
    }
}