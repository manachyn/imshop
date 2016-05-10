<?php

namespace im\users\clients;

use yii\authclient\clients\GoogleOAuth as BaseGoogle;

class Google extends BaseGoogle implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
//        $emails = isset($this->getUserAttributes()['emails']) ? $this->getUserAttributes()['emails'] : null;
//        if ($emails !== null && isset($emails[0])) {
//            return $emails[0];
//        } else {
//            return null;
//        }
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
        $name = [];
        if (isset($this->getUserAttributes()['name']['givenName'])) {
            $name[] = $this->getUserAttributes()['name']['givenName'];
        }
        if (isset($this->getUserAttributes()['name']['familyName'])) {
            $name[] = $this->getUserAttributes()['name']['familyName'];
        }
        return $name ? implode(' ', $name) : null;
    }
}