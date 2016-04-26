<?php

namespace im\seo\components;

use im\seo\models\Meta;

/**
 * Module cache manager.
 *
 * @package im\cms\components
 */
class CacheManager extends \im\base\cache\CacheManager
{
    /**
     * @inheritdoc
     */
    protected $dataCacheTags = [
        'im\seo\models\Meta' => 'getMetaCacheTags'
    ];

    /**
     * Returns meta cache tags.
     *
     * @param Meta $meta
     * @return array
     */
    public function getMetaCacheTags(Meta $meta)
    {
        return [
            Meta::className(),
            $meta::className(),
            $meta::className() . '::' . $meta->getPrimaryKey()
        ];
    }
}