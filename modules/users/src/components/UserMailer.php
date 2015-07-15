<?php

namespace im\users\components;

use im\users\models\User;
use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\mail\MailerInterface;

/**
 * Class Mailer
 * Component for sending emails to user.
 * @package im\users\components
 */
class UserMailer extends Component implements UserMailerInterface
{
    use ModuleTrait;

    /**
     * @var MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     */
    public $mailer = 'mailer';

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
    public $registrationConfirmationSubject = 'Registration confirmation';

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
        $view = 'registration_confirmation';
        $this->mailer->compose(['html' => $view, 'text' => 'text/' . $view], ['user' => $user, 'token' => $token])
            ->setTo($user->email)
            ->setFrom($this->sender)
            ->setSubject($this->registrationConfirmationSubject)
            ->send();
    }
}