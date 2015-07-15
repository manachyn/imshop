<?php

use tests\codeception\_pages\ResendPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that resending of confirmation tokens works');

$page = ResendPage::openBy($I);

$I->amGoingTo('try to resend token to non-existent user');
$page->resend('foo@example.com');
$I->see('User with such email or username is not found.');

$I->amGoingTo('try to resend token to already confirmed user');
$user = $I->getFixture('user')->getModel('user');
$page->resend($user->email);
$I->see('This account has already been confirmed.');

$I->amGoingTo('try to resend token to unconfirmed user');
$user = $I->getFixture('user')->getModel('unconfirmed');
$page->resend($user->email);
$I->see('A message has been sent to your e-mail address. It contains a confirmation link that you have to click to complete registration.');
