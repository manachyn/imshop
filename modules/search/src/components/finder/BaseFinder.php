<?php

namespace im\search\components\finder;

use im\search\components\searchable\SearchableInterface;
use Yii;

abstract class BaseFinder implements FinderInterface
{
    /**
     * @inheritdoc
     */
    public static function getTransformer($type)
    {
        return static::getSearchableType($type)->getDocumentToObjectTransformer();
    }

    /**
     * Returns searchable type by index type.
     *
     * @param string $type
     * @return SearchableInterface
     */
    public static function getSearchableType($type)
    {
        /** @var \im\search\components\SearchManager $search */
        $search = Yii::$app->get('search');

        return $search->getSearchableType($type);
    }
}