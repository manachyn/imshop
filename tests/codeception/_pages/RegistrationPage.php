<?php

namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents registration page.
 *
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class RegistrationPage extends BasePage
{
    /**
     * @inheritdoc
     */
    public $route = '/users/registration/register';

    /**
     * @param array $registrationData
     */
    public function submit(array $registrationData)
    {
        foreach ($registrationData as $field => $value) {
            $inputType = $field === 'body' ? 'textarea' : 'input';
            $this->actor->fillField($inputType . '[name="RegistrationForm[' . $field . ']"]', $value);
        }
        $this->actor->click('register-button');
    }
}
