<?php

namespace im\search\components\finder;

use im\search\components\searchable\SearchableInterface;
use Yii;

/**
 * Finder base class.
 *
 * @package im\search\components\finder
 */
abstract class BaseFinder implements FinderInterface
{
//    /**
//     * @inheritdoc
//     */
//    public static function getTransformer($type)
//    {
//        return static::getSearchableType($type)->getDocumentToObjectTransformer();
//    }

    /**
     * Returns searchable type.
     *
     * @param string $type
     * @return SearchableInterface
     */
    public static function getSearchableType($type)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $searchManager->getSearchableType($type);
    }
}