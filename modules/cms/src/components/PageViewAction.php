<?php

namespace im\cms\components;

use im\base\actions\ModelViewAction;
use im\base\context\ModelContextInterface;
use im\cms\models\Page;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * Class PageViewAction
 * @package im\cms\components
 */
class PageViewAction extends ModelViewAction implements ModelContextInterface
{
    /**
     * @var Page
     */
    private $_model;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $pageClass = Page::className();
        if ($this->modelClass != $pageClass && !is_subclass_of($this->modelClass, $pageClass)) {
            throw new InvalidConfigException("The 'modelClass' property class must be '$pageClass' or it's subclass.");
        }
    }

    /**
     * Displays a page.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path page path
     * @throws \yii\web\NotFoundHttpException
     * @return string page content
     */
    public function run($path = 'index')
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
            if ($template && $layout = $template->getLayout()) {
                $this->controller->layout = '//' . $layout->id;
            }
        }

        return $this->render($this->view, ['model' => $model]);
    }

    /**
     * Finds the Page model based on it's path.
     *
     * @param string $path
     * @return Page|null
     * @throws InvalidConfigException
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
     * @param Page $model
     *
     * @return array
     */
    protected function getRelationForLoad(Page $model)
    {
        $relations = [];
        if ($model->getBehavior('template')) {
            $relations[] = 'template';
        }

        return $relations;
    }

    /**
     * Loads page model with relations from database by it's path.
     *
     * @param string $path page path
     * @return Page|null the loaded model
     */
    protected function loadModel($path)
    {
        /* @var $modelClass Page */
        $modelClass = $this->modelClass;
        $parts = explode('/', $path);
        /** @var Page $model */
        $model = $modelClass::findBySlug(array_pop($parts))->published()->one();
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