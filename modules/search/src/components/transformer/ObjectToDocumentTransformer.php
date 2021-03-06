<?php

namespace im\search\components\transformer;

use im\eav\models\Value;
use im\search\components\index\Document;

class ObjectToDocumentTransformer implements ObjectToDocumentTransformerInterface
{
    /**
     * @var array
     */
    protected $options = [
        'identifier' => 'id'
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * @inheritdoc
     */
    public function transform($object, $attributes)
    {
        $identifier = $object->{$this->options['identifier']};
        $document = new Document($identifier);
        foreach ($attributes as $name => $attribute) {
            if ($attribute->value) {
                if ($attribute->value instanceof \Closure) {
                    $value = call_user_func($attribute->value, $object, $attribute);
                } else {
                    $value = $attribute->value;
                }
            } else {
                $value = $object->$name;
            }
            if ($value !== null) {
                if ($attribute->type) {
                    settype($value, $attribute->type);
                }
                $document->set($attribute->name, $value);
            }
        }

        return $document;
    }
}