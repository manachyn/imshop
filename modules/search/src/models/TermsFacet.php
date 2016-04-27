<?php

namespace im\search\models;

use im\eav\models\Attribute;
use im\search\components\query\facet\TermsFacetInterface;

/**
 * Terms facet model class.
 */
class TermsFacet extends Facet implements TermsFacetInterface
{
    const TYPE = self::TYPE_TERMS;

    /**
     * @inheritdoc
     */
    public function getValues()
    {
        $values = parent::getValues();
        if (!$values && strncmp($this->attribute_name, 'eAttributes.', 12) === 0) {
            $name = substr($this->attribute_name, 12);
            $attribute = Attribute::findByNameAndEntityType($name, $this->entity_type);
            if ($attribute->isValuesPredefined()) {
                $values = $attribute->getValues();
                if ($values) {
                    foreach ($values as $value) {
                        $values[] = new FacetTerm([
                            'facet' => $this,
                            'term' => $value->value,
                            'display' => $value->presentation
                        ]);
                    }
                    $this->populateRelation('values', $values);
                }
            }
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function getValueInstance(array $config)
    {
        return new FacetTerm($config);
    }

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '_form';
    }
}
