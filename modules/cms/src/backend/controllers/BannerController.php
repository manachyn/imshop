<?php

namespace im\cms\backend\controllers;

use im\base\controllers\CrudController;
use im\cms\models\Banner;
use im\cms\models\BannerSearch;
use im\cms\Module;

/**
 * Class BannerController
 * @package im\cms\backend\controllers
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class BannerController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('banner', 'Banner has been successfully created.');
        $this->errorCreate = Module::t('banner', 'Banner has not been created. Please try again!');
        $this->successUpdate = Module::t('banner', 'Banner has been successfully saved.');
        $this->successBatchUpdate = Module::t('banner', '{count} galleries have been successfully saved.');
        $this->errorUpdate = Module::t('banner', 'Banner has not been saved. Please try again!');
        $this->successDelete = Module::t('banner', 'Banner has been successfully deleted.');
        $this->successBatchDelete = Module::t('banner', 'Galleries have been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Banner::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return BannerSearch::className();
    }
}
