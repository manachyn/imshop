<?php

namespace im\users\clients;

use yii\authclient\clients\GitHub as BaseGitHub;

class GitHub extends BaseGitHub implements ClientInterface
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
        return isset($this->getUserAttributes()['login']) ? $this->getUserAttributes()['login'] : $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return null;
    }
}