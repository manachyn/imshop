<?php

namespace im\cms\components;

use im\base\actions\ModelViewAction;
use im\base\context\ModelContextInterface;
use im\cms\models\Page;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class PageModelViewAction
 * @package im\cms\components
 */
class PageModelViewAction extends ModelViewAction implements ModelContextInterface
{
    /**
     * @var Model
     */
    private $_model;

    /**
     * Displays a model page.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $path model path
     * @param string|Page $parentPage
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function run($path, $parentPage = null)
    {
        $model = $this->findModel($path);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $this->setModel($model);
        if ($model && $model->getBehavior('template')) {
            /** @var TemplateBehavior|Page $model */
            /** @var \im\cms\models\Template $template */
            $template = $model->template;
        } elseif ($parentPage && $parentPage->getBehavior('template')) {
            /** @var TemplateBehavior|Page $parentPage */
            /** @var \im\cms\models\Template $template */
            $template = $parentPage->template;
        }
        if (isset($template) && $layout = $template->getLayout()) {
            $this->controller->layout = '//' . $layout->id;
        }

        return $this->render($this->view, ['model' => $model, 'parentPage' => $parentPage]);
    }

    /**
     * Finds the model based on it's path.
     *
     * @param string $path
     * @return Model|null
     */
    public function findModel($path)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $cacheKey = [$this->modelClass, $path];
            return $cacheManager->getFromCache($this->modelClass, $cacheKey, function () use ($path) {
                return $this->loadModel($path);
            });
        }

        return $this->loadModel($path);
    }


    /**
     * @param Model $model
     * @return array
     */
    protected function getRelationForLoad(Model $model)
    {
        $relations = [];

        return $relations;
    }

    /**
     * Loads model with relations from database by it's path.
     *
     * @param string $path page path
     * @return Model|null the loaded model
     */
    protected function loadModel($path)
    {
        /* @var PageInterface $modelClass */
        $modelClass = $this->modelClass;
        /** @var Model $model */
        $model = $modelClass::findByPath($path)->one();
        if ($model) {
            foreach ($this->getRelationForLoad($model) as $relation) {
                $model->$relation;
            }
        }

        return $model;
    }

    /**
     * @inheritdoc
     */
    protected function render($viewName, array $params)
    {
        return $this->controller->render($viewName, $params);
    }

    /**
     * @inheritdoc
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @inheritdoc
     */
    public function setModel(Model $model)
    {
        $this->_model = $model;
    }
}