<?php

namespace im\users\components;

use im\users\models\Profile;

interface UserInterface
{
    /**
     * Sets username.
     *
     * @param string $username
     */
    public function setUsername($username);

    /**
     * Returns username.
     *
     * @return string
     */
    public function getUsername();

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
    public function isConfirmed();

    /**
     * Check user active status.
     *
     * @return bool whether the user is activated.
     */
    public function isActive();

    /**
     * Set user creation time.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Returns user creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set user updating time.
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt);

    /**
     * Returns user updating time.
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

    /**
     * Sets user last login time.
     *
     * @param \DateTime $lastLoginAt
     */
    public function setLastLoginAt(\DateTime $lastLoginAt);

    /**
     * Returns user last login time.
     *
     * @return \DateTime
     */
    public function getLastLoginAt();

    /**
     * Sets the IP from which the user was registered.
     *
     * @param string $registrationIp
     */
    public function setRegistrationIp($registrationIp);

    /**
     * Returns the IP from which the user was registered.
     *
     * @return string
     */
    public function getRegistrationIp();

    /**
     * Sets the IP from which the user was logined last time.
     *
     * @param string $lastLoginIp
     */
    public function setLastLoginIp($lastLoginIp);

    /**
     * Returns the IP from which the user was logined last time.
     *
     * @return string
     */
    public function getLastLoginIp();
}