<?php

namespace im\users\components;

use im\users\models\User;

/**
 * Interface UserMailerInterface for user emailer.
 * @package im\users\components
 */
interface UserMailerInterface
{
    /**
     * Sent registration confirmation email.
     *
     * @param User $user
     * @param string $token
     * @return bool
     */
    public function sendRegistrationConfirmationEmail(User $user, $token);
}