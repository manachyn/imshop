<?php

namespace im\users\clients;

use yii\authclient\clients\YandexOAuth as BaseYandex;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Yandex extends BaseYandex implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails']) ? $this->getUserAttributes()['emails'] : null;
        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login']) ? $this->getUserAttributes()['login'] : null;
    }

    /**
     * @return string|null
     */
    public function getFullName()
    {
        return isset($this->getUserAttributes()['real_name']) ? $this->getUserAttributes()['real_name'] : null;
    }
}