<?php

namespace im\users\codeception\frontend\unit\models;

use AspectMock\Test as test;
use Codeception\Specify;
use im\users\models\RecoveryForm;
use im\users\models\User;
use Yii;
use yii\codeception\TestCase;

/**
 * Class RecoveryFormTest
 * @package im\users\codeception\frontend\unit\models
 */
class RecoveryFormTest extends TestCase
{
    use Specify;

    /**
     * Tests validation rules for the model.
     */
    public function testRecoveryFormValidationRules()
    {
        $form = Yii::createObject(RecoveryForm::className());
        $this->specify('form is not valid when email is empty', function () use ($form) {
            $form->setAttributes(['email' => '']);
            verify($form->validate())->false();
            verify($form->getErrors('email'))->contains('E-mail or username cannot be blank.');
        });
        $this->specify('form is not valid when user does not exist', function () use ($form) {
            test::double(User::className(), ['findByUsername' => null, 'findByEmail' => null]);
            $form->setAttributes(['email' => 'foobar@example.com']);
            verify($form->validate())->false();
            verify($form->getErrors('email'))->contains('User with such email or username is not found.');
        });
        $this->specify('form is not valid when user is not confirmed', function () use ($form) {
            $user = Yii::createObject(User::className());
            test::double($user, ['isConfirmed' => false, 'isActive' => true]);
            test::double(User::className(), ['findByUsername' => $user]);
            $form->setAttributes(['email' => 'foobar@example.com']);
            verify($form->validate())->false();
            verify($form->getErrors('email'))->contains('This account in not confirmed.');
            $form->setUser(null);
            test::double($user, ['isConfirmed' => true, 'isActive' => true]);
            verify($form->validate())->true();
        });
        $this->specify('form is not valid when user is not activated', function () use ($form) {
            $form->setUser(null);
            $user = Yii::createObject(User::className());
            test::double($user, ['isConfirmed' => true, 'isActive' => false]);
            test::double(User::className(), ['findByUsername' => $user]);
            $form->setAttributes(['email' => 'foobar@example.com']);
            verify($form->validate())->false();
            verify($form->getErrors('email'))->contains('This account in not activated.');
            $form->setUser(null);
            test::double($user, ['isConfirmed' => true, 'isActive' => true]);
            verify($form->validate())->true();
        });
    }
}