<?php

namespace im\cms\components;

use im\base\actions\ModelViewAction;
use im\base\context\ModelContextInterface;
use im\cms\models\Page;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use Yii;

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
     *
     * @param string $path page path
     * @return string page content
     */
    public function run($path = 'index')
    {
        $model = $this->findModel($path);
        $this->setModel($model);

        return $this->render($this->view, $model);
    }

    /**
     * Finds the Page model based on it's path.
     *
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path page path
     * @throws \yii\web\NotFoundHttpException
     * @return Page the loaded model
     */
    public function findModel($path)
    {
        $model = $this->loadModel($path);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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

        return $modelClass::findByPath($path)->published()->one();
    }

    /**
     * Renders a view.
     *
     * @param string $viewName view name
     * @param Page $model model for render
     * @return string result of the rendering
     */
    protected function render($viewName, $model)
    {
        return $this->controller->render($viewName, ['model' => $model]);
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