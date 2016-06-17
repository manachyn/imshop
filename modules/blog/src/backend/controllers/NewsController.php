<?php

namespace im\blog\backend\controllers;

use im\base\controllers\CrudController;
use im\blog\models\News;
use im\blog\models\NewsSearch;
use im\blog\Module;
use Yii;

/**
 * Class NewsController implements the CRUD actions for News model.
 * @package im\blog\backend\controllers
 */
class NewsController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('article', 'News has been successfully created.');
        $this->errorCreate = Module::t('article', 'News has not been created. Please try again!');
        $this->successUpdate = Module::t('article', 'News has been successfully saved.');
        $this->successBatchUpdate = Module::t('article', '{count} news have been successfully saved.');
        $this->errorUpdate = Module::t('article', 'News has not been saved. Please try again!');
        $this->successDelete = Module::t('article', 'News has been successfully deleted.');
        $this->successBatchDelete = Module::t('article', 'News have been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return News::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return NewsSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        return Yii::createObject($this->getModelClass());
    }
}
