<?php

namespace im\users\components;

use im\users\models\RegistrationConfirmationEmail;
use im\users\models\User;
use Yii;
use yii\base\Component;
use yii\di\Instance;

/**
 * Class Mailer
 * Component for sending emails to user.
 * @package im\users\components
 */
class UserMailer extends Component implements UserMailerInterface
{
    /**
     * @var \yii\mail\BaseMailer mailer component
     */
    public $mailer;

    /**
     * @var string
     */
    public $viewPath = '@im/users/views/mail';

    /**
     * @var string|array sender
     * Defaults to '\Yii::$app->params['adminEmail']' or 'no-reply@example.com'.
     */
    public $sender;

    /**
     * @var string registration confirmation email subject
     */
    public $registrationConfirmationSubject;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\BaseMailer');
        $this->mailer->viewPath = $this->viewPath;
        $this->mailer->getView()->theme = Yii::$app->view->theme;
        if ($this->sender === null) {
            $this->sender = isset(Yii::$app->params['adminEmail']) ? Yii::$app->params['adminEmail'] : 'no-reply@example.com';
        }


    }

    /**
     * @inheritdoc
     */
    public function sendRegistrationConfirmationEmail(User $user, $token)
    {
        $email = new RegistrationConfirmationEmail();
        $view = 'registration_confirmation';
        $this->mailer->compose($email->view, ['user' => $user, 'token' => $token])
            ->setTo($user->email)
            ->setFrom($this->sender)
            ->setSubject($subject)
            ->send();
    }
}