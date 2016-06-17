<?php

namespace im\users\codeception\frontend\unit\models;

use AspectMock\Test as test;
use Codeception\Specify;
use im\users\models\RegistrationForm;
use im\users\tests\codeception\frontend\unit\TestCase;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class LoginFormTest
 * @package im\users\codeception\frontend\unit\models
 */
class RegistrationFormTest extends TestCase
{
    use Specify;

    /**
     * Tests validation rules for the model.
     */
    public function testRegistrationFormValidationRules()
    {
        $form = Yii::createObject(RegistrationForm::className());
        $this->specify('username is required', function () use ($form) {
            $form->setAttributes(['username' => '']);
            verify($form->validate(['username']))->false();
            verify($form->getErrors('username'))->contains('Username cannot be blank.');
        });

        $this->specify('username is too long', function () use ($form) {
            $tooLongString = function() { $string = ''; for($i = 0; $i <= 256; $i++) $string .= 'X'; return $string; };
            $form->username = $tooLongString();
            verify($form->validate(['username']))->false();
            verify($form->getErrors('username'))->contains('Username should contain at most 100 characters.');
        });

        $this->specify('username is too short', function () use ($form) {
            $form->username = 'A';
            verify($form->validate(['username']))->false();
            verify($form->getErrors('username'))->contains('Username should contain at least 2 characters.');
        });

        $this->specify('username is already using', function () use ($form) {
            $form->username = 'tester';
            test::double(ActiveQuery::className(), ['exists' => true]);
            verify($form->validate(['username']))->false();
            verify($form->getErrors('username'))->contains('Username "tester" has already been taken.');
        });

        test::double(ActiveQuery::className(), ['exists' => false]);
        $form->username = 'good_name';
        verify('username is ok', $form->validate(['username']))->true();

        $this->specify('email is required', function () use ($form) {
            $form->setAttributes(['email' => '']);
            verify($form->validate(['email']))->false();
            verify($form->getErrors('email'))->contains('E-mail cannot be blank.');
        });

        $this->specify('email is not email', function () use ($form) {
            $form->email = 'not valid email';
            verify($form->validate(['email']))->false();
            verify($form->getErrors('email'))->contains('E-mail is not a valid email address.');
        });

        $this->specify('email is already using', function () use ($form) {
            $form->email = 'tester@example.com';
            test::double(ActiveQuery::className(), ['exists' => true]);
            verify($form->validate(['email']))->false();
            verify($form->getErrors('email'))->contains('E-mail "tester@example.com" has already been taken.');
        });

        test::double(ActiveQuery::className(), ['exists' => false]);
        $form->username = 'tester@example.com';
        verify('email is ok', $form->validate(['email']))->true();

        $this->specify('password is required', function () use ($form) {
            $form->setAttributes(['password' => '']);
            verify($form->validate(['password']))->false();
            verify($form->getErrors('password'))->contains('Password cannot be blank.');
        });

        $this->specify('password2 is required', function () use ($form) {
            $form->setAttributes(['password2' => '']);
            verify($form->validate(['password2']))->false();
            verify($form->getErrors('password2'))->contains('Repeated password cannot be blank.');
        });

        $this->specify('password is too short', function () use ($form) {
            $form->password = '12345';
            $form->password2 = '12345';
            verify($form->validate(['password']))->false();
            verify($form->getErrors('password'))->contains('Password should contain at least 6 characters.');
        });

        $form->password = '123456';
        $form->password2 = '123456';
        verify('password is ok', $form->validate(['password']))->true();

//        $this->specify('password is not required when passwordAutoGenerating is true', function () use ($form) {
//            Yii::$app->getModule('users')->passwordAutoGenerating = true;
//            $form->setAttributes(['password' => '']);
//            verify($form->validate(['password']))->true();
//            verify($form->getErrors('password'))->contains('Password cannot be blank.');
//        });
    }
}
