<?php

namespace im\users\clients;

use yii\authclient\clients\VKontakte as BaseVKontakte;

class VKontakte extends BaseVKontakte implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $scope = 'email';

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getAccessToken()->getParam('email');
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