<?php

namespace im\cms\components;

use im\base\types\EntityType;
use im\cms\models\Page;
use yii\base\Component;
use Yii;
use yii\base\InvalidParamException;

/**
 * Class Cms
 * @package im\cms\components
 */
class Cms extends Component
{
    /**
     * @var CacheManager
     */
    private $_cacheManager = 'im\cms\components\CacheManager';

    /**
     * Registers page type.
     *
     * @param EntityType $type
     */
    public function registerPageType($type)
    {
        $baseClass = Page::className();
        if ($type->getClass() !== $baseClass && !is_subclass_of($type->getClass(), $baseClass)) {
            throw new InvalidParamException("Class '{$type->getClass()}' must extend '$baseClass'");
        }
        $type->setGroup('page');
        Yii::$app->get('typesRegister')->registerEntityType($type);
    }

    /**
     * Returns registered page types.
     *
     * @return \im\base\types\EntityType[]
     */
    public function getPageTypes()
    {
        return Yii::$app->get('typesRegister')->getEntityTypes('page');
    }

    /**
     * Create page instance by type.
     *
     * @param string $type
     * @return Page
     */
    public function getPageInstance($type = null)
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $class = $type && $typesRegister->hasEntityType($type) ? $typesRegister->getEntityClass($type) : Page::className();

        return Yii::createObject(['class' => $class, 'type' => $type]);
    }

    /**
     * Returns cache manager.
     *
     * @return CacheManager|null
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
     * @param CacheManager|string|array|bool $cacheManager
     */
    public function setCacheManager($cacheManager)
    {
        $this->_cacheManager = $cacheManager;
    }
}

