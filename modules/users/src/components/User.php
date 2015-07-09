<?php

namespace im\users\components;

use yii\web\IdentityInterface;

/**
 * Extended class for the "user" application component.
 *
 * @package im\users\components
 */
class User extends \yii\web\User
{
    /**
     * This method is called after the user is successfully registered.
     * The default implementation will trigger the [[EVENT_AFTER_REGISTRATION]] event.
     * @param IdentityInterface $identity the user identity information
     */
    public function afterLogin($identity, $cookieBased, $duration)
    {
        $this->trigger(self::EVENT_AFTER_LOGIN, new UserEvent([
            'identity' => $identity,
            'cookieBased' => $cookieBased,
            'duration' => $duration,
        ]));
    }
}