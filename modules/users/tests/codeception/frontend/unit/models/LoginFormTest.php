<?php

namespace im\users\codeception\frontend\unit\models;

use AspectMock\Test as test;
use Codeception\Specify;
use im\users\models\LoginForm;
use im\users\models\User;
use Yii;

/**
 * Class LoginFormTest
 * @package im\users\codeception\frontend\unit\models
 */
class LoginFormTest extends \Codeception\TestCase\Test
{
    use Specify;

    /**
     * Tests validation rules for the model.
     */
    public function testLoginFormValidationRules()
    {
        $form = Yii::createObject(LoginForm::className());
        $this->specify('username is required', function () use ($form) {
            $form->setAttributes(['username' => '']);
            verify($form->validate())->false();
            verify($form->getErrors('username'))->contains('Username cannot be blank.');
        });
        $this->specify('password is required', function () use ($form) {
            $form->setAttributes(['password' => '']);
            verify($form->validate())->false();
            verify($form->getErrors('password'))->contains('Password cannot be blank.');
        });
        $this->specify('user should exist in database', function () use ($form) {
            $user = test::double(User::className(), ['findByUsername' => null, 'findByEmail' => null]);
            $form->setAttributes(['username' => 'tester', 'password' => 'qwerty']);
            verify($form->validate())->false();
            verify($form->getErrors('password'))->contains('Incorrect username or password.');
            $user->verifyInvoked('findByUsername');
            $user->verifyInvoked('findByEmail');
        });
        $this->specify('password should be valid', function () use ($form) {
            $user = Yii::createObject(User::className());
            test::double($user, ['hasAttribute' => true, 'isConfirmed' => true, 'isActive' => true]);
            $user->setPassword('qwerty1');
            test::double(User::className(), ['findByUsername' => $user]);
            $form->setAttributes(['password' => 'qwerty']);
            verify($form->validate(['password']))->false();
            verify($form->getErrors('password'))->contains('Incorrect username or password.');
            $user->setPassword('qwerty');
            verify($form->validate(['password']))->true();
        });
        $this->specify('user should be confirmed', function () use ($form) {
            $form->setAttributes(['username' => 'tester', 'password' => 'qwerty']);
            $form->setUser(null);
            $user = Yii::createObject(User::className());
            test::double($user, ['hasAttribute' => true, 'isConfirmed' => false]);
            $user->username = 'tester';
            $user->setPassword('qwerty');
            test::double(User::className(), ['findByUsername' => $user]);
            verify($form->validate())->false();
            verify($form->getErrors('username'))->contains('This account in not confirmed.');
        });
        $this->specify('user should not be active', function () use ($form) {
            $form->setAttributes(['username' => 'tester', 'password' => 'qwerty']);
            $form->setUser(null);
            $user = Yii::createObject(User::className());
            test::double($user, ['hasAttribute' => true, 'isConfirmed' => true, 'isActive' => false]);
            $user->username = 'tester';
            $user->setPassword('qwerty');
            test::double(User::className(), ['findByUsername' => $user]);
            verify($form->validate())->false();
            verify($form->getErrors('username'))->contains('This account in not activated.');
            test::double($user, ['isConfirmed' => true, 'isActive' => true]);
            verify($form->validate())->true();
        });
    }
}
