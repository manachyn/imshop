<?php

namespace im\cms\backend\controllers;

use im\base\controllers\CrudController;
use im\cms\models\PageSearch;
use im\cms\Module;
use im\cms\models\Page;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class PageController implements the CRUD actions for Page model.
 *
 * @package im\cms\backend\controllers
 */
class PageController extends CrudController
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->successCreate))
            $this->successCreate = Module::t('page', 'Page has been successfully created.');
        if (!isset($this->errorCreate))
            $this->errorCreate = Module::t('page', 'Page has not been created. Please try again!');
        if (!isset($this->successUpdate))
            $this->successUpdate = Module::t('page', 'Page has been successfully saved.');
        if (!isset($this->successBatchUpdate))
            $this->successBatchUpdate = Module::t('page', '{count} pages have been successfully saved.');
        if (!isset($this->errorUpdate))
            $this->errorUpdate = Module::t('page', 'Page has not been saved. Please try again!');
        if (!isset($this->successDelete))
            $this->successDelete = Module::t('page', 'Page has been successfully deleted.');
        if (!isset($this->successBatchDelete))
            $this->successBatchDelete = Module::t('page', 'Pages have been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Page::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return PageSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function createModel()
    {
        $type = Yii::$app->request->get('type');

        return Yii::$app->get('cms')->getPageInstance($type);
    }

    /**
     * @inheritdoc
     */
    protected function findModels($id, $with = [])
    {
        /** @var Page $modelClass */
        $modelClass = $this->getModelClass();
        $query = $modelClass::find()->andWhere(['id' => $id]);
        $query->type = null;
        if ($with) {
            $query->with($with);
        }
        if (is_array($id)) {
            $model = $query->all();
        } else {
            $model = $query->one();
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

    /**
     * Get attributes for batch editing.
     *
     * @param array $models
     * @return array
     */
    protected function getBatchEditableAttributes(array $models)
    {
        return [
            'model' => ['title', 'content', 'status', 'layout_id'],
            'pageMeta' => ['meta_title', 'meta_keywords', 'meta_description', 'meta_robots', 'custom_meta'],
            'openGraph' => ['title', 'type', 'url', 'image', 'description', 'site_name', 'video']
        ];
    }

    /**
     * Find models for batch updating.
     *
     * @param integer $id
     * @param array $data
     * @return \yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModelsToBatchUpdate($id, array $data)
    {
        $with = [];
        if (isset($data['OpenGraph'])) {
            $with = ['pageMeta', 'pageMeta.openGraph'];
        } elseif (isset($data['PageMeta'])) {
            $with = ['pageMeta'];
        }

        return $this->findModels($id, $with);
    }
}
