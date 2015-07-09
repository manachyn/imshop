<?php

namespace im\users\components;

use im\users\models\User;
use yii\base\Component;
use yii\base\Event;

/**
 * Class UserEventsHandler handles user events.
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

    public function afterUserRegistration ($event)
    {
    }

    public function beforeUserRegistration ($event)
    {
        $event->is
    }
}