<?php

namespace im\users\components;

use im\users\models\ModuleTrait;
use im\users\models\Token;
use im\users\models\User;
use yii\base\Component;
use yii\base\Event;
use yii\base\ModelEvent;
use yii\di\Instance;

/**
 * Class UserEventsHandler handles user events.
 *
 * @package im\users\components
 */
class UserEventsHandler extends Component
{
    use ModuleTrait;

    /**
     * @var \im\users\components\UserMailerInterface
     */
    public $mailer;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->mailer = Instance::ensure($this->mailer, 'im\users\components\UserMailerInterface');
        Event::on(User::className(), User::EVENT_BEFORE_REGISTRATION, [$this, 'beforeUserRegistration']);
        Event::on(User::className(), User::EVENT_AFTER_REGISTRATION, [$this, 'afterUserRegistration']);
    }

    /**
     * @param Event $event
     */
    public function afterUserRegistration (Event $event)
    {
        /** @var User $user */
        $user = $event->sender;
        if ($this->getModule()->registrationConfirmation) {
            $token = Token::generate($user->getId(), Token::TYPE_REGISTRATION_CONFIRMATION);
            $this->mailer->sendRegistrationConfirmationEmail($user, $token);
        }
    }

    /**
     * @param ModelEvent $event
     */
    public function beforeUserRegistration (ModelEvent $event)
    {
    }
}