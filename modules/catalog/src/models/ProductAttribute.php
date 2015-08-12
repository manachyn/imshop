<?php

namespace im\catalog\models;

use im\eav\models\Attribute;

/**
 * Product attribute model class.
 */
class ProductAttribute extends Attribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['entity_type', 'default', 'value' => Product::getEntityType()]
        ]);
    }
}
