<?php

namespace im\users\clients;

use yii\authclient\clients\Twitter as BaseTwitter;

class Twitter extends BaseTwitter implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['screen_name']) ? $this->getUserAttributes()['screen_name'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }
}