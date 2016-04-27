<?php

namespace im\catalog\backend\controllers;

use im\catalog\models\Category;
use im\catalog\models\CategorySearch;
use im\catalog\Module;
use im\tree\controllers\CrudTreeController;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends CrudTreeController
{
//    /**
//     * @inheritdoc
//     */
//    public $treeSerializer = 'im\catalog\models\serialization\CategoryToTreeNode';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->successCreate))
            $this->successCreate = Module::t('category', 'Category has been successfully created.');
        if (!isset($this->errorCreate))
            $this->errorCreate = Module::t('category', 'Category has not been created. Please try again!');
        if (!isset($this->successUpdate))
            $this->successUpdate = Module::t('category', 'Category has been successfully saved.');
        if (!isset($this->successBatchUpdate))
            $this->successBatchUpdate = Module::t('category', '{count} categories have been successfully saved.');
        if (!isset($this->errorUpdate))
            $this->errorUpdate = Module::t('category', 'Category has not been saved. Please try again!');
        if (!isset($this->successDelete))
            $this->successDelete = Module::t('category', 'Category has been successfully deleted.');
        if (!isset($this->successBatchDelete))
            $this->successBatchDelete = Module::t('category', 'Categories have been successfully deleted.');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Category::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return CategorySearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        return \Yii::createObject($this->getModelClass());
    }
}
