<?php

use yii\helpers\Url;

/** @var im\users\Module $module */
$module = \Yii::$app->getModule('users');

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that confirmation works');

$I->amGoingTo('check that error is showed when token expired');
$token = $I->getFixture('token')->getModel('expired_confirmation');
$I->amOnPage(Url::toRoute(['/users/registration/confirm', 'token' => $token->token]));
$I->see('The confirmation link is invalid or expired. Please try to request a new one.');

$I->amGoingTo('check that user get confirmed');
$token = $I->getFixture('token')->getModel('confirmation');
$I->amOnPage(Url::toRoute(['/users/registration/confirm', 'token' => $token->token]));
$I->see('Thank you! Registration is complete now.');
if ($module->loginAfterRegistration) {
    $I->see('Logout');
} else {
    $I->dontSee('Logout');
}
