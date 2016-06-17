<?php

namespace im\cms\backend\controllers;

use im\base\controllers\CrudController;
use im\cms\models\PageSearch;
use im\cms\Module;
use im\cms\models\Page;
use Yii;
use yii\helpers\ArrayHelper;
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
        $this->successCreate = Module::t('page', 'Page has been successfully created.');
        $this->errorCreate = Module::t('page', 'Page has not been created. Please try again!');
        $this->successUpdate = Module::t('page', 'Page has been successfully saved.');
        $this->successBatchUpdate = Module::t('page', '{count} pages have been successfully saved.');
        $this->errorUpdate = Module::t('page', 'Page has not been saved. Please try again!');
        $this->successDelete = Module::t('page', 'Page has been successfully deleted.');
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

    /**
     * @inheritdoc
     */
    protected function saveModels($models, $create = false, $parameters = [])
    {
        /** @var Page $node */
        $node = ArrayHelper::remove($models, 'model');

        return $this->saveNode($node, $node->getParentId()) && parent::saveModels($models);
    }

    /**
     * @param Page $node
     * @param int|null $parent
     * @return bool
     */
    protected function saveNode($node, $parent = null)
    {
        if (!empty($parent)) {
            $parent = $this->findModel($parent);
            return $node->appendTo($parent, false);
        } elseif (!$node->isRoot()) {
            return $node->makeRoot(false);
        } else {
            return $node->save(false);
        }
    }
}
