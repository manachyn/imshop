<?php

namespace im\users\codeception\frontend\unit\models;

use AspectMock\Test as test;
use Codeception\Specify;
use im\users\models\ResendForm;
use im\users\models\User;
use Yii;

/**
 * Class ResendFormTest
 * @package im\users\codeception\frontend\unit\models
 */
class ResendFormTest extends \Codeception\TestCase\Test
{
    use Specify;

    /**
     * Tests validation rules for the model.
     */
    public function testResendFormValidationRules()
    {
        $form = Yii::createObject(ResendForm::className());
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
        $this->specify('form is not valid when user is confirmed already', function () use ($form) {
            $user = Yii::createObject(User::className());
            test::double($user, ['isConfirmed' => true]);
            test::double(User::className(), ['findByUsername' => $user]);
            $form->setAttributes(['email' => 'foobar@example.com']);
            verify($form->validate())->false();
            verify($form->getErrors('email'))->contains('This account has already been confirmed.');
            $form->setUser(null);
            test::double($user, ['isConfirmed' => false]);
            verify($form->validate())->true();
        });
    }
}