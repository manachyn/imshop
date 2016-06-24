<?php

namespace im\catalog\components\search;

use im\search\components\service\db\IndexedSearchableType;

/**
 * Class IndexedProduct
 * @package im\catalog\components\search
 */
class IndexedProduct extends IndexedSearchableType
{
    use SearchableProductTrait;
}
