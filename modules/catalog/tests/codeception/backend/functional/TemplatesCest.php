<?php

namespace im\cms\tests\codeception\backend\functional;

use im\cms\models\Template;
use im\cms\models\widgets\BannerWidget;
use im\cms\models\widgets\ContentWidget;
use im\cms\models\widgets\WidgetArea;
use im\cms\models\widgets\WidgetAreaItem;
use im\cms\tests\codeception\backend\_pages\templates\TemplateCreatePage;
use im\cms\tests\codeception\backend\_pages\templates\TemplateUpdatePage;
use im\cms\tests\codeception\backend\FunctionalTester;
use yii\helpers\Url;

/**
 * Class TemplatesCest
 * @package im\cms\tests\codeception\backend\functional
 */
class TemplatesCest extends FunctionalCest
{
    public function testList(FunctionalTester $I)
    {
        $I->wantTo('ensure that templates index page works');
        $I->amOnPage(['cms/template/index']);
        $I->seeInTitle('Templates');
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that template create page works');

        $page = TemplateCreatePage::openBy($I);

        $I->see('Template creation');

        $I->amGoingTo('submit form with no data');
        $page->submit([
            'Template' => [
                'name' => ''
            ],
        ]);
        $I->expectTo('see validation errors');
        $I->see('Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $templateData = [
            'name' => 'Test template',
            'layout_id' => 'test-layout',
            'default' => 1
        ];
        $page->submit(['Template' => $templateData]);
        $I->expectTo('see that template is created');
        $I->seeRecord(Template::className(), $templateData);

        $I->expectTo('see update page');
        $I->see('Template updating');
        $I->see('Template has been successfully created.');
    }

    public function testCRUD(FunctionalTester $I)
    {
        $I->wantTo('ensure that template managing works');

        $page = TemplateCreatePage::openBy($I);

        $I->see('Template creation');

        $I->see('Available widgets', 'h4');
        $I->see('Content widget', '.available-widget');
        $I->see('Banner widget', '.available-widget');

        $I->see('Widgets', 'h4');
        $I->see('Sidebar area', '.widget-area');
        $I->see('Footer area', '.widget-area');

        $I->amGoingTo('submit form with widgets data');
        $templateData = [
            'name' => 'Test template ' . microtime(true),
            'layout_id' => 'test-layout',
            'default' => 1
        ];
        $widgetAreasData = [
            'test-layout-sidebar' => [
                'display' => WidgetArea::DISPLAY_ALWAYS,
                'code' => 'test-layout-sidebar'
            ],
            'test-layout-footer' => [
                'display' => WidgetArea::DISPLAY_ALWAYS,
                'code' => 'test-layout-footer'
            ]
        ];
        $widgetsData = [
            'test-layout-sidebar' => [
                1 => [
                    'content' => 'Content widget 1 ' . microtime(true),
                    'type' => 'content',
                    'sort' => 1
                ],
                2 => [
                    'banner_id' => time(),
                    'type' => 'banner',
                    'sort' => 2
                ]
            ],
            'test-layout-footer' => [
                1 => [
                    'content' => 'Content widget 2 ' . microtime(true),
                    'type' => 'content',
                    'sort' => 1
                ]
            ]
        ];
        $page->submitForm([
            'Template' => $templateData,
            'WidgetArea' => $widgetAreasData,
            'Widgets' => $widgetsData
        ]);
        $I->expectTo('see that template is created');
        $I->seeRecord(Template::className(), $templateData);

        $templateRecord = $I->grabRecord(Template::className(), $templateData);
        $widgetAreasData['test-layout-sidebar']['template_id'] = $templateRecord->id;
        $widgetAreasData['test-layout-footer']['template_id'] = $templateRecord->id;

        $I->seeRecord(WidgetArea::className(), $widgetAreasData['test-layout-sidebar']);
        $I->seeRecord(WidgetArea::className(), $widgetAreasData['test-layout-footer']);

        $widget1Data = $widgetsData['test-layout-sidebar'][1];
        $widget2Data = $widgetsData['test-layout-sidebar'][2];
        $widget3Data = $widgetsData['test-layout-footer'][1];
        unset($widget1Data['sort'], $widget2Data['sort'], $widget3Data['sort'], $widget1Data['type'], $widget2Data['type'], $widget3Data['type']);
        $I->seeRecord(ContentWidget::className(), $widget1Data);
        $I->seeRecord(BannerWidget::className(), $widget2Data);
        $I->seeRecord(ContentWidget::className(), $widget3Data);

        $widgetArea1Record = $I->grabRecord(WidgetArea::className(), $widgetAreasData['test-layout-sidebar']);
        $widgetArea2Record = $I->grabRecord(WidgetArea::className(), $widgetAreasData['test-layout-footer']);
        $widget1Record = $I->grabRecord(ContentWidget::className(), $widget1Data);
        $widget2Record = $I->grabRecord(BannerWidget::className(), $widget2Data);
        $widget3Record = $I->grabRecord(ContentWidget::className(), $widget3Data);

        $I->seeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget1Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 1
        ]);
        $I->seeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget2Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 2
        ]);
        $I->seeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget3Record->id,
            'widget_area_id' => $widgetArea2Record->id,
            'sort' => 1
        ]);

        $I->expectTo('see update page');
        $I->see('Template updating');
        $I->see('Template has been successfully created.');

        $I->see('Widgets', 'h4');
        $I->see('Content widget', '//*[contains(text(),"Sidebar area")]/parent::*/*/*[contains(@class, "selected-widget")][1]');
        $I->see('Banner widget', '//*[contains(text(),"Sidebar area")]/parent::*/*/*[contains(@class, "selected-widget")][2]');
        $I->see('Content widget', '//*[contains(text(),"Footer area")]/parent::*/*/*[contains(@class, "selected-widget")][1]');

        $I->amGoingTo('update selected widgets');
        $newWidgetsData = $widgetsData;
        $newWidgetsData['test-layout-sidebar'][1] = array_merge($widgetsData['test-layout-sidebar'][1], [
            'id' => $widget1Record->id,
            'content' => 'Content widget 1 ' . microtime(true),
            'sort' => 2
        ]);
        $newWidgetsData['test-layout-sidebar'][2] = array_merge($widgetsData['test-layout-sidebar'][2], [
            'id' => $widget2Record->id,
            'sort' => 1
        ]);
        $newWidgetsData['test-layout-footer'] = [];

        $page->submitForm([
            'Widgets' => $newWidgetsData
        ]);

        $I->expectTo('see that widgets are updated');
        $I->dontSeeRecord(ContentWidget::className(), $widget1Data);
        $I->seeRecord(ContentWidget::className(), array_diff_key($newWidgetsData['test-layout-sidebar'][1], array_flip(['sort', 'type'])));
        $I->dontSeeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget1Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 1
        ]);
        $I->dontSeeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget2Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 2
        ]);
        $I->seeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget1Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 2
        ]);
        $I->seeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget2Record->id,
            'widget_area_id' => $widgetArea1Record->id,
            'sort' => 1
        ]);
        $I->dontSeeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget3Record->id,
            'widget_area_id' => $widgetArea2Record->id
        ]);

        $I->amGoingTo('delete template with widgets');
        $id = $I->grabFromCurrentUrl('#(\d+)#');

        $I->sendAjaxPostRequest(Url::to(['/cms/template/delete', 'id' => $id]));

        $I->expectTo('see that template is deleted');
        $I->dontSeeRecord(Template::className(), $templateData);

        $I->expectTo('see that widget areas are deleted');
        $I->dontSeeRecord(WidgetArea::className(), $widgetAreasData['test-layout-sidebar']);
        $I->dontSeeRecord(WidgetArea::className(), $widgetAreasData['test-layout-footer']);

        $I->expectTo('see that widgets are deleted');
        $I->dontSeeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget1Record->id,
            'widget_area_id' => $widgetArea1Record->id
        ]);
        $I->dontSeeRecord(WidgetAreaItem::className(), [
            'widget_id' => $widget2Record->id,
            'widget_area_id' => $widgetArea1Record->id
        ]);
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that template update page works');

        $page = TemplateCreatePage::openBy($I);

        $I->see('Template creation');

        $I->amGoingTo('create test template');
        $page->submit([
            'Template' => [
                'name' => 'Test template',
                'layout_id' => 'test-layout',
                'default' => 1
            ]
        ]);

        $I->amGoingTo('open update page');
        $id = $I->grabFromCurrentUrl('#(\d+)#');
        $page = TemplateUpdatePage::openBy($I, ['id' => $id]);

        $I->see('Template updating');

        $I->amGoingTo('submit form with no data');
        $page->submit([
            'Template' => [
                'name' => ''
            ]
        ]);
        $I->expectTo('see validation errors');
        $I->see('Name cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $templateData = [
            'name' => 'Test template',
            'layout_id' => 'test-layout',
            'default' => 1
        ];
        $page->submit(['Template' => $templateData]);
        $I->expectTo('see that template is updated');
        $I->seeRecord(Template::className(), $templateData);

        $I->expectTo('see update page');
        $I->see('Template updating');
        $I->see('Template has been successfully saved.');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->wantTo('ensure that template deleting works');

        $page = TemplateCreatePage::openBy($I);

        $I->see('Template creation');

        $I->amGoingTo('create test template');
        $templateData = [
            'name' => 'template-delete-tester'
        ];
        $page->submit([
            'Template' => $templateData
        ]);
        $I->see('Template updating');

        $I->amGoingTo('delete template');
        $id = $I->grabFromCurrentUrl('#(\d+)#');

        $I->sendAjaxPostRequest(Url::to(['/cms/template/delete', 'id' => $id]));

        $I->expectTo('see that template is deleted');
        $I->dontSeeRecord(Template::className(), [
            'name' => 'template-delete-tester'
        ]);
    }
}
