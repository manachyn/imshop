<?php

namespace im\users\components;

use im\users\models\User;
use yii\base\Component;
use yii\base\Event;
use yii\base\ModelEvent;

/**
 * Class UserEventsHandler handles user events.
 *
 * @package im\users\components
 */
class UserEventsHandler extends Component
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(User::className(), User::EVENT_BEFORE_REGISTRATION, [$this, 'beforeUserRegistration']);
        Event::on(User::className(), User::EVENT_AFTER_REGISTRATION, [$this, 'afterUserRegistration']);
    }

    /**
     * @param Event $event
     */
    public function afterUserRegistration (Event $event)
    {

    }

    /**
     * @param ModelEvent $event
     */
    public function beforeUserRegistration (ModelEvent $event)
    {
    }
}