<?php

namespace im\users\clients;

use yii\authclient\clients\LinkedIn as BaseLinkedIn;

class LinkedIn extends BaseLinkedIn implements ClientInterface
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
        return $this->getEmail();
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        $name = [];
        if (isset($this->getUserAttributes()['first_name'])) {
            $name[] = $this->getUserAttributes()['first_name'];
        }
        if (isset($this->getUserAttributes()['last_name'])) {
            $name[] = $this->getUserAttributes()['last_name'];
        }
        return $name ? implode(' ', $name) : null;
    }
}