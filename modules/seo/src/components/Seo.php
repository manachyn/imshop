<?php

namespace im\seo\components;

use im\base\cache\CacheManager;
use im\base\context\ModelContextInterface;
use im\seo\models\Meta;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\View;

/**
 * Class Seo
 * @package im\seo\components
 */
class Seo extends Component
{
    /**
     * @var array social meta types
     */
    public $socialMetaTypes = [
        'open_graph' => 'im\seo\models\OpenGraph',
        'twitter_card' => 'im\seo\models\TwitterCard'
    ];

    /**
     * @var array
     */
    public $metaTypeSocialMetaTypes = [];

    /**
     * @var \im\base\cache\CacheManager
     */
    public $_cacheManager = 'im\seo\components\CacheManager';

    /**
     * Registers social meta types.
     *
     * @param string $type
     * @param string $class
     */
    public function registerSocialMetaType($type, $class)
    {
        $this->socialMetaTypes[$type] = $class;
    }

    /**
     * Registers social meta classes.
     *
     * @param string $type
     * @return mixed
     */
    public function getSocialMetaClass($type)
    {
        if (isset($this->socialMetaTypes[$type])) {
            return $this->socialMetaTypes[$type];
        } elseif (class_exists($type)) {
            return $type;
        } else {
            throw new InvalidParamException("Social meta type '$type' is not registered");
        }
    }

    /**
     * Returns social meta types.
     *
     * @param string $metaType
     * @return array
     */
    public function getSocialMetaTypes($metaType)
    {
        return isset($this->metaTypeSocialMetaTypes[$metaType]) ? $this->metaTypeSocialMetaTypes[$metaType]
            : array_keys($this->socialMetaTypes);
    }

    /**
     * Applies meta to view.
     *
     * @param View $view
     */
    public function applyContextMetaToView(View $view)
    {
        if ($view->context instanceof ModelContextInterface) {
            $model = $view->context->getModel();
        } elseif ($view->context instanceof Controller && $view->context->action instanceof ModelContextInterface) {
            /** @var ModelContextInterface $action */
            $action = $view->context->action;
            $model = $action->getModel();
        }
        if (!empty($model) && $meta = $this->getMeta($model)) {
            $meta->applyTo($view);
        }
    }

    /**
     * Returns cache manager.
     *
     * @return \im\base\cache\CacheManager|null
     */
    public function getCacheManager()
    {
        if ($this->_cacheManager === false) {
            return null;
        } elseif (!$this->_cacheManager instanceof CacheManager) {
            $this->_cacheManager = Yii::createObject($this->_cacheManager);
        }

        return $this->_cacheManager;
    }

    /**
     * Returns model meta.
     *
     * @param Model $model
     * @return Meta|null
     */
    private function getMeta(Model $model)
    {
        if ($model->getBehavior('seo')) {
            $cacheManager = $this->getCacheManager();
            if ($cacheManager) {
                $cacheKey = [$model::className()];
                if ($model instanceof ActiveRecord) {
                    /** @var ActiveRecord $model */
                    $cacheKey[] = $model->getPrimaryKey();
                } else {
                    $cacheKey[] = spl_object_hash($model);
                }
                $cacheKey[] = Meta::className();
                return $cacheManager->getFromCache(Meta::className(), $cacheKey, function () use ($model) {
                    return $this->loadMeta($model);
                });
            }

            return $this->loadMeta($model);
        }

        return null;
    }

    /**
     * Loads model meta from db.
     *
     * @param Model $model
     * @return Meta
     */
    private function loadMeta(Model $model)
    {
        /** @var SeoBehavior $model */
        $meta = $model->getMeta();
        if ($meta) {
            $meta->socialMeta;
        }

        return $meta;
    }
}