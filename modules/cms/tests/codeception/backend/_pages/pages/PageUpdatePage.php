<?php

namespace im\cms\tests\codeception\backend\_pages\pages;

/**
 * Represents contact page
 * @property \im\cms\tests\codeception\backend\AcceptanceTester|\im\cms\tests\codeception\backend\FunctionalTester $actor
 */
class PageUpdatePage extends PageCreatePage
{
    public $route = 'cms/page/update';
}
