<?php

namespace tests\codeception\acceptance;
use im\users\models\User;
use tests\codeception\_pages\RegistrationPage;

/**
 * Regisration Cest.
 *
 * @package tests\codeception\acceptance
 */
class RegisrationCest
{
    /**
     * This method is called before each cest class test method
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
    }

    /**
     * This method is called after each
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll([
            'email' => 'tester.email@example.com',
            'username' => 'tester',
        ]);
    }

    /**
     * This method is called when test fails.
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {

    }

    /**
     *
     * @param \FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserRegistration($I, $scenario)
    {
        $I->wantTo('ensure that registration works');

        $registrationPage = RegistrationPage::openBy($I);
        $I->see('Sign up', 'h1');

        $I->amGoingTo('submit registration form with no data');

        $registrationPage->submit([]);

        $I->expectTo('see validations errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('E-mail cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');
        $I->see('Repeated password cannot be blank.', '.help-block');


        $I->amGoingTo('submit registration form with not correct email and repeated password');
        $registrationPage->submit([
            'username' => 'tester',
            'email' => 'tester.email',
            'password' => 'tester_password',
            'password2' => 'tester_password2'
        ]);

        $I->expectTo('see that email address and repeated password is wrong');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
        $I->dontSee('Repeated password cannot be blank.', '.help-block');
        $I->see('E-mail is not a valid email address.', '.help-block');
        $I->see('Repeated password must be repeated exactly.', '.help-block');

        $I->amGoingTo('submit registration form with correct data');
        $registrationPage->submit([
            'username' => 'tester',
            'email' => 'tester.email@example.com',
            'password' => 'tester_password',
            'password2' => 'tester_password'
        ]);

        $registrationPage = RegistrationPage::openBy($I);

        $I->amGoingTo('submit registration form with same data');
        $registrationPage->submit([
            'username' => 'tester',
            'email' => 'tester.email@example.com',
            'password' => 'tester_password',
            'password2' => 'tester_password'
        ]);

        $I->expectTo('see that username and email address have already been taken');
        $I->see('Username "tester" has already been taken.', '.help-block');
        $I->see('E-mail "tester.email@example.com" has already been taken.', '.help-block');

//        $I->expectTo('see that user logged in');
//        $I->seeLink('Logout (tester)');
    }
}
