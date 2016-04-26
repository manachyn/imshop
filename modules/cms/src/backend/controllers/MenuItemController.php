<?php

namespace im\cms\backend\controllers;

use im\cms\models\MenuItem;
use im\cms\Module;
use im\tree\controllers\CrudTreeController;
use Yii;

/**
 * MenuItemController implements the CRUD actions for MenuItem model.
 */
class MenuItemController extends CrudTreeController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->successCreate))
            $this->successCreate = Module::t('menu-item', 'Menu item has been successfully created.');
        if (!isset($this->errorCreate))
            $this->errorCreate = Module::t('menu-item', 'Menu item has not been created. Please try again!');
        if (!isset($this->successUpdate))
            $this->successUpdate = Module::t('menu-item', 'Menu item has been successfully saved.');
        if (!isset($this->successBatchUpdate))
            $this->successBatchUpdate = Module::t('menu-item', '{count} menu items have been successfully saved.');
        if (!isset($this->errorUpdate))
            $this->errorUpdate = Module::t('menu-item', 'Menu item has not been saved. Please try again!');
        if (!isset($this->successDelete))
            $this->successDelete = Module::t('menu-item', 'Menu item has been successfully deleted.');
        if (!isset($this->successBatchDelete))
            $this->successBatchDelete = Module::t('menu-item', 'Menu items have been successfully deleted.');
        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return MenuItem::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        /** @var MenuItem $model */
        $model = Yii::createObject($this->getModelClass());
        if ($menu = Yii::$app->request->get('menu')) {
            $model->menu_id = $menu;
        }

        return $model;
    }
}
