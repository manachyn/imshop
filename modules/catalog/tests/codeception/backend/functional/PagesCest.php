<?php

namespace im\cms\tests\codeception\backend\functional;

use im\cms\models\Page;
use im\cms\models\PageMeta;
use im\cms\tests\codeception\backend\_pages\pages\PageCreatePage;
use im\cms\tests\codeception\backend\_pages\pages\PageUpdatePage;
use im\cms\tests\codeception\backend\FunctionalTester;
use im\seo\models\OpenGraph;
use im\seo\models\TwitterCard;
use yii\helpers\Url;

/**
 * Class PagesCest
 * @package tests\codeception\backend\functional
 */
class PagesCest extends FunctionalCest
{
    public function testList(FunctionalTester $I)
    {
        $I->wantTo('ensure that pages index page works');
        $I->amOnPage(['cms/page/index']);
        $I->seeInTitle('Pages');
    }

    public function testCreate(FunctionalTester $I)
    {
        $I->wantTo('ensure that page create page works');

        $page = PageCreatePage::openBy($I);

        $I->see('Page creation');

        $I->amGoingTo('submit form with no data');
        $page->submit([
            'Page' => [
                'title' => ''
            ],
        ]);
        $I->expectTo('see validation errors');
        $I->see('Title cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $pageData = [
            'title' => 'Test page',
            'content' => 'Content',
            'status' => 1
        ];
        $pageMetaData = [
            'meta_title' => 'Meta title',
            'meta_keywords' => 'Meta keywords',
            'meta_description' => 'Meta description',
            'custom_meta' => 'Custom meta',
            'metaRobotsDirectives' => 'noindex'
        ];
        $openGraphData = [
            'title' => 'Open graph title',
            'type' => 'Open graph type',
            'url' => 'Open graph url',
            'image' => 'Open graph image',
            'description' => 'Open graph description',
            'site_name' => 'Open graph site name',
            'video' => 'Open graph video',
        ];
        $twitterCardData = [
            'card' => 'Twitter card card',
            'site' => 'Twitter card site',
            'description' => 'Twitter card description',
            'creator' => 'Twitter card creator',
            'image' => 'Twitter card image',
        ];
        $page->submit([
            'Page' => $pageData,
            'PageMeta' => $pageMetaData,
            'OpenGraph' => $openGraphData,
            'TwitterCard' => $twitterCardData
        ]);
        $I->expectTo('see that page is created');
        $I->seeRecord(Page::className(), $pageData);
        $pageRecord = $I->grabRecord(Page::className(), $pageData);
        $pageMetaRecord['meta_robots'] = $pageMetaData['metaRobotsDirectives'];
        $pageMetaData['entity_id'] = $pageRecord->id;
        unset($pageMetaData['metaRobotsDirectives']);
        $I->seeRecord(PageMeta::className(), $pageMetaData);
        $pageMetaRecord = $I->grabRecord(PageMeta::className(), $pageMetaData);
        $I->seeRecord(OpenGraph::className(), array_merge($openGraphData, [
            'meta_id' => $pageMetaRecord->id,
            'meta_type' => 'page_meta',
            'social_type' => 'open_graph'
        ]));
        $I->seeRecord(TwitterCard::className(), array_merge($twitterCardData, [
            'meta_id' => $pageMetaRecord->id,
            'meta_type' => 'page_meta',
            'social_type' => 'twitter_card'
        ]));

        $I->expectTo('see update page');
        $I->see('Page updating');
        $I->see('Model has been successfully created.');
    }

    public function testUpdate(FunctionalTester $I)
    {
        $I->wantTo('ensure that page update page works');

        $page = PageCreatePage::openBy($I);

        $I->see('Page creation');

        $I->amGoingTo('create test page');
        $page->submit([
            'Page' => [
                'title' => 'Test page'
            ]
        ]);

        $I->amGoingTo('open update page');
        $id = $I->grabFromCurrentUrl('#(\d+)#');
        $page = PageUpdatePage::openBy($I, ['id' => $id]);

        $I->see('Page updating');

        $I->amGoingTo('submit form with no data');
        $page->submit([
            'Page' => [
                'title' => ''
            ]
        ]);
        $I->expectTo('see validation errors');
        $I->see('Title cannot be blank.', '.help-block');

        $I->amGoingTo('submit form with correct data');
        $pageData = [
            'title' => 'Test page',
            'content' => 'Content',
            'status' => 0
        ];
        $page->submit(['Page' => $pageData]);
        $I->expectTo('see that page is updated');
        $I->seeRecord(Page::className(), $pageData);

        $I->expectTo('see update page');
        $I->see('Page updating');
        $I->see('Model has been successfully saved.');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->wantTo('ensure that page delete page works');

        $page = PageCreatePage::openBy($I);

        $I->see('Page creation');

        $I->amGoingTo('create test page');
        $pageData = ['title' => 'page-delete-tester'];
        $page->submit([
            'Page' => $pageData
        ]);
        $I->see('Page updating');

        $pageRecord = $I->grabRecord(Page::className(), $pageData);
        $pageMetaRecord = $I->grabRecord(PageMeta::className(), ['entity_id' => $pageRecord->id]);

        $I->amGoingTo('delete page');
        $id = $I->grabFromCurrentUrl('#(\d+)#');

        $I->sendAjaxPostRequest(Url::to(['/cms/page/delete', 'id' => $id]));

        $I->expectTo('see that page is deleted');
        $I->dontSeeRecord(Page::className(), [
            'title' => 'page-delete-tester'
        ]);
        $I->dontSeeRecord(PageMeta::className(), ['entity_id' => $pageRecord->id]);
        $I->dontSeeRecord(OpenGraph::className(), [
            'meta_id' => $pageMetaRecord->id,
            'meta_type' => 'page_meta',
            'social_type' => 'open_graph'
        ]);
        $I->dontSeeRecord(TwitterCard::className(), [
            'meta_id' => $pageMetaRecord->id,
            'meta_type' => 'page_meta',
            'social_type' => 'twitter_card'
        ]);
    }

}