<?php

namespace im\search\models;

use im\search\components\query\facet\FacetValueTrait;
use im\search\components\query\facet\IntervalFacetValueInterface;
use yii\base\Model;

/**
 * Class FacetInterval
 * @package im\search\models
 */
class FacetInterval extends Model implements IntervalFacetValueInterface
{
    use FacetValueTrait;
}
