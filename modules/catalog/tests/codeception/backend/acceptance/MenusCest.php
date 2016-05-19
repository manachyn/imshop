<?php

namespace im\cms\tests\codeception\backend\acceptance;

use im\cms\models\MenuItem;
use im\cms\tests\codeception\backend\_pages\menus\MenuCreatePage;
use im\cms\tests\codeception\backend\_pages\menus\MenuUpdatePage;
use im\cms\tests\codeception\backend\AcceptanceTester;

/**
 * Class MenusCest
 * @package im\cms\tests\codeception\backend\acceptance
 */
class MenusCest extends AcceptanceCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function testCRUD(AcceptanceTester $I)
    {
        $I->wantTo('ensure that menu managing works');

        $page = MenuCreatePage::openBy($I);

        $I->see('Menu creation');

        $I->amGoingTo('create menu');
        $menuData = [
            'name' => 'menu-delete-tester ' . microtime(true),
            'location' => 'test-menu'
        ];
        $page->submit(['Menu' => $menuData]);

        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }

        $I->amGoingTo('open update page');
        $id = $I->grabFromCurrentUrl('#(\d+)#');
        $page = MenuUpdatePage::openBy($I, ['id' => $id]);

        $I->see('Menu updating');
        $I->see('Menu items');

        $I->amGoingTo('create menu item');
        $page->clickCreateItem();
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }

        $I->see('Menu item creation');
        $I->amGoingTo('submit menu item form with no data');

        $menuItemData = ['label' => ''];
        $page->submitMenuItem(['MenuItem' => $menuItemData]);
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }
        $I->expectTo('see validation errors');
        $I->see('Label cannot be blank.', '.help-block');

        $I->amGoingTo('submit menu item form with correct data');
        $menuItemMainData = [
            'label' => 'Menu item ' . microtime(true),
            'title' => 'Title',
            'url' => 'test-page',
            'target_blank' => 1,
            'css_classes' => 'menu-item',
            'rel' => 'nofollow',
            'status' => MenuItem::STATUS_ACTIVE,
            'visibility' => 'admin'
        ];
        $menuItemDisplayData = [
            'items_display' => MenuItem::DISPLAY_DROPDOWN,
            'items_css_classes' => 'menu-sub-item',
            'uploadedIcon' => 'home-icon.png',
            'uploadedActiveIcon' => 'home-icon-active.png',
            'uploadedVideo' => 'sample-video-360x240-2mb.mp4'
        ];
        $page->fillMainFields(['MenuItem' => $menuItemMainData]);
        $page->fillDisplayFields(['MenuItem' => $menuItemDisplayData]);
        $page->clickSubmitItem();
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }

        $I->expectTo('see menu item update form');
        $I->see('Menu item updating');

        $I->expectTo('see created menu item');
        $I->see($menuItemMainData['label'], '//*[@id="menu-items-tree"]/ul/li[1]/a');

        $I->amGoingTo('update menu item');
        $I->click($menuItemMainData['label']);
        $page->clickEditItem();
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }
        $I->expectTo('see menu item update form');
        $I->see('Menu item updating');
        $I->amGoingTo('submit menu item form with updated data');
        $menuItemMainDataUpdated = [
            'label' => 'Menu item updated ' . microtime(true),
        ];
        $page->fillMainFields(['MenuItem' => $menuItemMainDataUpdated]);
        $page->clickSubmitItem();
        if (method_exists($I, 'wait')) {
            $I->wait(2); // only for selenium
        }
        $I->expectTo('see updated menu item');
        $I->see($menuItemMainDataUpdated['label'], '//*[@id="menu-items-tree"]/ul/li[1]/a');

        $I->amGoingTo('create menu sub item');
        $I->click($menuItemMainDataUpdated['label']);
        $page->clickCreateItem();
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }

        $I->see('Menu item creation');
        $I->amGoingTo('submit menu item form');
        $menuSubItemMainData = $menuItemMainData;
        $menuSubItemMainData['label'] = 'Menu sub item ' . microtime(true);

        $page->fillMainFields(['MenuItem' => $menuSubItemMainData]);
        $page->clickSubmitItem();
        if (method_exists($I, 'wait')) {
            $I->wait(3); // only for selenium
        }

        $I->expectTo('see menu item update form');
        $I->see('Menu item updating');
        $I->expectTo('see created menu sub item');
        $I->see($menuSubItemMainData['label'], '//*[@id="menu-items-tree"]/ul/li[1]/ul/li[1]/a');

        $I->amGoingTo('delete menu item');
        $I->click($menuSubItemMainData['label']);
        $page->clickDeleteItem();
        $I->acceptPopup();
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }
        $I->dontSee($menuSubItemMainData['label'], '//*[@id="menu-items-tree"]/ul/li[1]/ul/li[1]/a');
    }
}