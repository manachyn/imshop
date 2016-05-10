<?php

namespace im\cms\tests\codeception\backend\functional;

use im\cms\models\Menu;
use im\cms\tests\codeception\backend\_pages\menus\MenuCreatePage;
use im\cms\tests\codeception\backend\_pages\menus\MenuUpdatePage;
use im\cms\tests\codeception\backend\FunctionalTester;
use yii\helpers\Url;

/**
 * Class MenusCest
 * @package im\cms\tests\codeception\backend\functional
 */
class MenusCest extends FunctionalCest
{
    public function testList(FunctionalTester $I)
    {
        $I->wantTo('ensure that menus index page works');
        $I->amOnPage(['cms/menu/index']);
        $I->seeInTitle('Menus');
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that menu create page works');

        $page = MenuCreatePage::openBy($I);

        $I->see('Menu creation');

        $I->amGoingTo('submit form with no data');
        $menuData = ['name' => ''];
        $page->submit(['Menu' => $menuData]);
        $I->expectTo('see validation errors');
        $I->see('Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $menuData = [
            'name' => 'Test menu ' . microtime(true),
            'location' => 'test-menu'
        ];
        $page->submit(['Menu' => $menuData]);

        $I->expectTo('see that menu is created');
        $I->seeRecord(Menu::className(), $menuData);

        $I->expectTo('see update page');
        $I->see('Menu updating');
        $I->see('Menu has been successfully created.');
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that menu update page works');

        $page = MenuCreatePage::openBy($I);

        $I->see('Menu creation');

        $I->amGoingTo('create menu');
        $menuData = [
            'name' => 'Test menu . ' . microtime(true),
            'location' => 'test-menu'
        ];
        $page->submit(['Menu' => $menuData]);

        $I->amGoingTo('open menu update page');
        $id = $I->grabFromCurrentUrl('#(\d+)#');
        $page = MenuUpdatePage::openBy($I, ['id' => $id]);

        $I->see('Menu updating');

        $I->amGoingTo('submit form with no data');
        $menuData = ['name' => ''];
        $page->submit(['Menu' => $menuData]);
        $I->expectTo('see validation errors');
        $I->see('Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $menuData = [
            'name' => 'Test menu updated ' . microtime(true),
            'location' => 'test-menu'
        ];
        $page->submit(['Menu' => $menuData]);
        $I->expectTo('see that menu is updated');
        $I->seeRecord(Menu::className(), $menuData);

        $I->expectTo('see update page');
        $I->see('Menu updating');
        $I->see('Menu has been successfully saved.');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->wantTo('ensure that menu deleting works');

        $page = MenuCreatePage::openBy($I);

        $I->see('Menu creation');

        $I->amGoingTo('create menu');
        $menuData = [
            'name' => 'menu-delete-tester ' . microtime(true),
            'location' => 'test-menu'
        ];
        $page->submit(['Menu' => $menuData]);

        $I->see('Menu updating');

        $I->amGoingTo('delete menu');
        $id = $I->grabFromCurrentUrl('#(\d+)#');

        $I->sendAjaxPostRequest(Url::to(['/cms/menu/delete', 'id' => $id]));

        $I->expectTo('see that menu is deleted');
        $I->dontSeeRecord(Menu::className(), $menuData);
    }
}
