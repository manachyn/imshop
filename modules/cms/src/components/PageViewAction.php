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
     * @param string $childPath
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($path = 'index', $childPath = null)
    {
        $model = $this->findModel($path);
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        if ($route = $model::getViewRoute()) {
            return Yii::$app->runAction($route, array_merge(Yii::$app->request->getQueryParams(), ['model' => $model]));
        }
        $this->setModel($model);
        $this->setTemplate($model);

        return $this->render($this->view, ['model' => $model]);
    }

    /**
     * Finds the Page model based on it's path.
     *
     * @param string $path
     * @return Page|null
     */
    public function findModel($path)
    {
        /** @var \im\cms\components\PageFinder $finder */
        $finder = Yii::$app->get('pageFinder');

        return $finder->findByPath($path);
    }

    /**
     * Set controller layout based on model template.
     *
     * @param Model $model
     */
    protected function setTemplate(Model $model)
    {
        if ($model && $model->getBehavior('template')) {
            /** @var TemplateBehavior $model */
            /** @var \im\cms\models\Template $template */
            $template = $model->template;
            if ($template && $layout = $template->getLayout()) {
                $this->controller->layout = '//' . $layout->id;
            }
        }
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
