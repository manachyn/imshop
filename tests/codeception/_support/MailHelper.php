<?php

namespace tests\codeception\_support;

use \Codeception\Module;
use yii\mail\MessageInterface;

class MailHelper extends Module
{
    /**
     * @var MessageInterface[]
     */
    public static $mails = [];

    /**
     * Asserts that last message contains $needle
     *
     * @param $needle
     */
    public function seeInEmail($needle)
    {
        /** @var MessageInterface $email */
        $email = end(static::$mails);
        $this->assertContains($needle, $email->toString());
    }


    /**
     * Asserts that last message subject contains $needle
     *
     * @param $needle
     */
    public function seeInEmailSubject($needle)
    {
        /** @var MessageInterface $email */
        $email = end(static::$mails);
        $this->assertContains($needle, $email->getSubject());
    }
    /**
     * Asserts that last message recipients contain $needle
     *
     * @param $needle
     */
    public function seeInEmailRecipients($needle)
    {
        /** @var MessageInterface $email */
        $email = end(static::$mails);
        $this->assertContains($needle, array_keys($email->getTo()));
    }
}
