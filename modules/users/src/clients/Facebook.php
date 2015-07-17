<?php

namespace im\users\clients;

use yii\authclient\clients\Facebook as BaseFacebook;

class Facebook extends BaseFacebook implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['username']) ? $this->getUserAttributes()['username'] : $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }
}