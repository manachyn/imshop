<?php

namespace im\search\models;

use im\search\components\query\facet\EditableFacetValueInterface;
use im\search\Module;
use Yii;

/**
 * Facet range model class.
 *
 * @property string $term
 */
class FacetTerm extends FacetValue implements EditableFacetValueInterface
{
    const TYPE = 'facet_term';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['term'], 'required'],
            [['term'], 'string', 'max' => 255]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'term' => Module::t('facet-value', 'Term')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->term;
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->term = $key;
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@im/search/backend/views/facet-term/_form';
    }
}
