<?php

namespace im\cms\tests\codeception\backend\acceptance;

use im\cms\tests\codeception\backend\_pages\templates\TemplateCreatePage;
use im\cms\tests\codeception\backend\AcceptanceTester;

/**
 * Class TemplatesCest
 * @package tests\codeception\acceptance\admin
 */
class TemplatesCest extends AcceptanceCest
{
    /**
     * @param AcceptanceTester $I
     */
    public function testCRUD(AcceptanceTester $I)
    {
        $I->wantTo('ensure that template managing works');

        $page = TemplateCreatePage::openBy($I);

        $I->see('Template creation');

        $I->selectOption('select[name="Template[layout_id]"]', 'test-layout');
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }

        $I->see('Available widgets', 'h4');
        $I->see('Content widget', '.available-widget');
        $I->see('Banner widget', '.available-widget');

        $I->see('Widgets', 'h4');
        $I->see('Sidebar area', '.widget-area');
        $I->see('Footer area', '.widget-area');

        $I->amGoingTo('select widgets');
        $I->dragAndDrop(
            '//*[contains(text(), "Available widgets")]/parent::*/descendant::*[contains(text(), "Content widget")]/ancestor::*[contains(@class, "available-widget")][1]',
            '//*[contains(text(), "Sidebar area")]/parent::*/*[contains(@class, "drop-area")][1]'
        );
        if (method_exists($I, 'wait')) {
            $I->wait(1); // only for selenium
        }
        $I->amGoingTo('fill widget data');
        $I->fillField('input[name="Widgets[test-layout-sidebar][1][content]"]', 'Test content widget');

        $I->amGoingTo('submit form');
        $templateData = [
            'name' => 'Test template',
            'default' => 1
        ];
        $page->submit(['Template' => $templateData]);
        if (method_exists($I, 'wait')) {
            $I->wait(2); // only for selenium
        }

        $I->expectTo('see update page');
        $I->see('Template updating');
        $I->see('Template has been successfully created.');

        $I->see('Widgets', 'h4');
        $I->see('Content widget', '//*[contains(text(),"Sidebar area")]/parent::*/*/*[contains(@class, "selected-widget")][1]');
        $I->seeInField('input[name="Widgets[test-layout-sidebar][1][content]"]', 'Test content widget');
    }
}
