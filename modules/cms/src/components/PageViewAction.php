<?php

namespace im\cms\components;

use im\base\actions\ModelViewAction;
use im\cms\components\layout\Layout;
use im\cms\models\Page;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\web\NotFoundHttpException;
use Yii;

class PageViewAction extends ModelViewAction
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $pageClass = Page::className();
        if ($this->modelClass != $pageClass && !is_subclass_of($this->modelClass, $pageClass))
            throw new InvalidConfigException("The 'modelClass' property class must be '$pageClass' or it's subclass.");
    }

    /**
     * Displays a page.
     * @param string $path the primary key of the model.
     * @return string
     */
    public function run($path = 'index')
    {
        $model = $this->findModel($path);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->hasProperty('useLayout')) {
            /** @var Layout $layout */
            $layout = $model->getLayout(false);
            if ($layout !== null)
                $this->controller->layout = '//' . $layout->id;
        }
        elseif ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        $output = $this->render($this->view, $model);
        //sleep(2);

        return $output;
    }

    /**
     * Finds the Page model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $path
     * @throws \yii\web\NotFoundHttpException
     * @return Page the loaded model
     */
    public function findModel($path)
    {
        $model = Yii::$app->cms->enablePageCache ? $this->loadModelFromCache($path) : $this->loadModel($path);
//        // Кеширование запросов
//        // Здесь недостаток этого варианты в том, что запрос на проверку зависимости кэша выполняется 3 раза
//        // (при каждом запросе Page, PageMeta, OpenGraph)
//        $db = \Yii::$app->db;
//        $duration = 10;
//        $dependency = new DbDependency;
//        $dependency->sql = 'SELECT MAX(updated_at) FROM tbl_pages';
//        $result = $db->cache(function ($db) use ($modelClass, $path) {
//            $model = $modelClass::findByPath($path)->published()->with('pageMeta', 'pageMeta.openGraph')->one();
//            return $model;
//        }, $duration, $dependency);
//
//        return $result;

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * Loads page model with relations from database by it's path
     * @param string $path
     * @return Page|null the loaded model
     */
    protected function loadModel($path)
    {
        /* @var $modelClass Page */
        $modelClass = $this->modelClass;
        return $modelClass::findByPath($path)->published()->with('pageMeta', 'pageMeta.openGraph')->one();
    }

    /**
     * Loads page model from cache
     * @param string $path
     * @return Page|null the loaded model
     */
    protected function loadModelFromCache($path)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->cms;
        /* @var $cache Cache */
        $cache = is_string($cms->pageCache) ? Yii::$app->get($cms->pageCache, false) : $cms->pageCache;
        $dependency = is_array($cms->pageCacheDependency)
            ? Yii::createObject($cms->pageCacheDependency) : $cms->pageCacheDependency;
        if ($cache instanceof Cache) {
            $key = $this->getCacheKey($path);
            $model = $cache->get($key);
            if ($model === false) {
                $model = $this->loadModel($path);
                if ($model !== null)
                    $cache->set($key, $model, $cms->pageCacheDuration, $dependency);
            }
        }
        return $model;
    }

    /**
     * Renders a view
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
     * Returns the cache key for the specified page path.
     * @param string $path the page name
     * @return mixed the cache key
     */
    protected function getCacheKey($path)
    {
        return [
            $this->modelClass,
            $path
        ];
    }
} 