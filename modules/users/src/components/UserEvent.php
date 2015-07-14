<?php

namespace im\users\components;

use yii\base\ModelEvent;

/**
 * Class UserEvent
 * @package im\users\components
 */
class UserEvent extends ModelEvent
{
    /**
     * @var \im\users\models\User the user object associated with this event
     */
    public $user;
}
