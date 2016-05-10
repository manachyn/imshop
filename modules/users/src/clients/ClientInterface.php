<?php

namespace im\users\clients;

use yii\authclient\ClientInterface as BaseInterface;

/**
 * Enhances default yii client interface by adding methods that can be used to
 * get user's email, username and full name.
 *
 * @package im\users\clients
 */
interface ClientInterface extends BaseInterface
{
    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @return string|null
     */
    public function getUsername();

    /**
     * @return string|null
     */
    public function getFullName();
}