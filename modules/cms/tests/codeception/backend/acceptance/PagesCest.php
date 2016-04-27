<?php

namespace tests\codeception\acceptance\admin;

use im\cms\tests\codeception\acceptance\AcceptanceCest;
use im\cms\tests\codeception\backend\AcceptanceTester;
use yii\helpers\Url;

class PagesCest extends AcceptanceCest
{
    public function testIndexPage(AcceptanceTester $I)
    {
        $I->wantTo('ensure that pages index page works');
        $I->amOnPage(Url::to(['/cms/page/index']));
        $I->seeInTitle('Pages');
    }
}