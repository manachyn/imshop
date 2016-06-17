<?php

namespace im\blog\backend\controllers;

use im\base\controllers\CrudController;
use im\blog\models\Article;
use im\blog\models\ArticleSearch;
use im\blog\Module;
use Yii;

/**
 * Class ArticleController implements the CRUD actions for Article model.
 * @package im\blog\backend\controllers
 */
class ArticleController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('article', 'Article has been successfully created.');
        $this->errorCreate = Module::t('article', 'Article has not been created. Please try again!');
        $this->successUpdate = Module::t('article', 'Article has been successfully saved.');
        $this->successBatchUpdate = Module::t('article', '{count} articles have been successfully saved.');
        $this->errorUpdate = Module::t('article', 'Article has not been saved. Please try again!');
        $this->successDelete = Module::t('article', 'Article has been successfully deleted.');
        $this->successBatchDelete = Module::t('article', 'Articles have been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Article::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ArticleSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        return Yii::createObject($this->getModelClass());
    }
}
