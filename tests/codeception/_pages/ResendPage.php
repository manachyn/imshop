<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents resend page.
 *
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class ResendPage extends BasePage
{
    /**
     * @inheritdoc
     */
    public $route = '/users/registration/resend';

    /**
     * @param string $email
     */
    public function resend($email)
    {
        $this->actor->fillField('input[name="ResendForm[email]"]', $email);
        $this->actor->click('resend-button');
    }
}
