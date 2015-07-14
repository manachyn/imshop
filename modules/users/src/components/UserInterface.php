<?php

namespace im\users\components;

use im\users\models\Profile;

interface UserInterface
{
    /**
     * Validates password.
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password);

    /**
     * Sets user password.
     *
     * @param string $password
     */
    public function setPassword($password);

    /**
     * Sets user authentication key.
     */
    public function setAuthKey();

    /**
     * Sets user new password reset token.
     */
    public function setPasswordResetToken();

    /**
     * Removes password reset token.
     */
    public function removePasswordResetToken();

    /**
     * Sets user profile.
     *
     * @param ProfileInterface $profile
     */
    public function setProfile(ProfileInterface $profile);

    /**
     * Returns user profile.
     *
     * @return ProfileInterface
     */
    public function getProfile();

    /**
     * Check user confirmation status.
     *
     * @return bool whether the user is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === static::STATUS_ACTIVE;
    }
}