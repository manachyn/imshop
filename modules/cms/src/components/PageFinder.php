<?php

namespace im\cms\components;

use im\cms\models\Page;
use Yii;
use yii\base\Component;
use yii\base\Model;

/**
 * Class PageFinder
 * @package im\cms\components
 */
class PageFinder extends Component
{
    /**
     * @var string
     */
    protected $modelClass = Page::class;

    /**
     * @param string $modelClass
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Finds models by condition.
     *
     * @param array $condition
     * @return Page[]
     */
    public function findModels(array $condition = [])
    {
        if ($cacheManager = $this->getCacheManager()) {
            $cacheKey = array_merge([$this->modelClass], $condition);
            return $cacheManager->getFromCache($this->modelClass, $cacheKey, function () use ($condition) {
                return $this->loadModels($condition);
            });
        }

        return $this->loadModels($condition);
    }

    /**
     * Finds model by condition.
     *
     * @param array $condition
     * @return Page|null
     */
    public function findModel(array $condition = [])
    {
        if ($cacheManager = $this->getCacheManager()) {
            $cacheKey = array_merge([$this->modelClass], $condition);
            return $cacheManager->getFromCache($this->modelClass, $cacheKey, function () use ($condition) {
                return $this->loadModel($condition);
            });
        }

        return $this->loadModel($condition);
    }

    /**
     * Finds model based on it's path.
     *
     * @param string $path
     * @return Page|null
     */
    public function findByPath($path)
    {
        if ($cacheManager = $this->getCacheManager()) {
            $cacheKey = [$this->modelClass, $path];
            return $cacheManager->getFromCache($this->modelClass, $cacheKey, function () use ($path) {
                return $this->loadModelByPath($path);
            });
        }

        return $this->loadModelByPath($path);
    }

    /**
     * Loads model with relations from database by condition.
     *
     * @param array $condition
     * @return Page|null
     */
    protected function loadModel(array $condition = [])
    {
        /* @var $modelClass Page */
        $modelClass = $this->modelClass;
        $model = $modelClass::find()->where($condition)->one();
        if ($model) {
            foreach ($this->getRelationForLoad($model) as $relation) {
                $model->$relation;
            }
        }

        return $model;
    }

    /**
     * Loads models with relations from database by condition.
     *
     * @param array $condition
     * @return Page[]
     */
    protected function loadModels(array $condition = [])
    {
        /* @var $modelClass Page */
        $modelClass = $this->modelClass;
        $query = $modelClass::find()->where($condition);
        $prototype = new $modelClass;
        foreach ($this->getRelationForLoad($prototype) as $relation) {
            $query->with($relation);
        }

        return $query->all();
    }

    /**
     * Loads model with relations from database by it's path.
     *
     * @param string $path
     * @return Page|null
     */
    protected function loadModelByPath($path)
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
     * @param Model $model
     * @return array
     */
    protected function getRelationForLoad(Model $model)
    {
        $relations = [];
        if ($model->getBehavior('template')) {
            $relations[] = 'template';
        }

        return $relations;
    }

    /**
     * Get cache manager.
     *
     * @return CacheManager|null
     */
    protected function getCacheManager()
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');

        return $cms->getCacheManager();
    }
}
