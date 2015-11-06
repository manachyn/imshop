<?php

namespace im\search\models;

use im\search\components\query\facet\EditableFacetValueInterface;
use im\search\components\query\facet\RangeFacetValueInterface;
use im\search\Module;
use Yii;

/**
 * Facet range model class.
 *
 * @property string $lower_bound
 * @property string $upper_bound
 * @property integer $include_lower_bound
 * @property integer $include_upper_bound
 */
class FacetRange extends FacetValue implements RangeFacetValueInterface, EditableFacetValueInterface
{
    const TYPE = 'facet_range';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->include_lower_bound = true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['include_lower_bound', 'include_upper_bound'], 'integer'],
            [['lower_bound', 'upper_bound'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'lower_bound' => Module::t('facet-value', 'Lower bound'),
            'upper_bound' => Module::t('facet-value', 'Upper bound'),
            'include_lower_bound' => Module::t('facet-value', 'Include lower bound'),
            'include_upper_bound' => Module::t('facet-value', 'Include upper bound')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getLowerBound()
    {
        return $this->lower_bound;
    }

    /**
     * @inheritdoc
     */
    public function getUpperBound()
    {
        return $this->upper_bound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeLowerBound()
    {
        return (bool) $this->include_lower_bound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeUpperBound()
    {
        return (bool) $this->include_upper_bound;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->_key ?: (($this->lower_bound ?: '*') . '-' . ($this->upper_bound ?: '*'));
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/search/backend/views/facet-range/_form';
    }
}
