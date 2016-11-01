<?php

namespace im\cms\backend\controllers;

use im\base\controllers\CrudController;
use im\cms\models\Gallery;
use im\cms\models\GallerySearch;
use im\cms\Module;

/**
 * Class GalleryController implements the CRUD actions for Gallery model.
 *
 * @package im\cms\backend\controllers
 */
class GalleryController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('gallery', 'Gallery has been successfully created.');
        $this->errorCreate = Module::t('gallery', 'Gallery has not been created. Please try again!');
        $this->successUpdate = Module::t('gallery', 'Gallery has been successfully saved.');
        $this->successBatchUpdate = Module::t('gallery', '{count} galleries have been successfully saved.');
        $this->errorUpdate = Module::t('gallery', 'Gallery has not been saved. Please try again!');
        $this->successDelete = Module::t('gallery', 'Gallery has been successfully deleted.');
        $this->successBatchDelete = Module::t('gallery', 'Galleries have been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Gallery::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return GallerySearch::className();
    }
}
