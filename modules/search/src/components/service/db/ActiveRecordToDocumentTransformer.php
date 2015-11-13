<?php

namespace im\search\components\service\db;

use im\eav\models\Value;
use im\search\components\index\Document;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ActiveRecordToDocumentTransformer implements ObjectToDocumentTransformerInterface
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
        $value = null;
        $identifier = $object->{$this->options['identifier']};
        $document = new Document($identifier);
        foreach ($attributes as $name => $attribute) {
            $toName = $attribute->name;
            if ($attribute->value) {
                if ($attribute->value instanceof \Closure) {
                    $value = call_user_func($attribute->value, $object, $attribute);
                } else {
                    $value = $attribute->value;
                }
            } else {
                $fromNameParts = explode('.', $name);
                $value = null;
                foreach ($fromNameParts as $key => $fromNamePart) {
                    if ($key === 0) {
                        $value = $object->$fromNamePart;
                    } elseif ($value !== null) {
                        if (is_array($value)) {
                            $newValue = [];
                            foreach ($value as $item) {
                                $newValue[] = is_object($item) ? $item->$fromNamePart : (is_array($item) ? $item[$fromNamePart] : $fromNamePart);
                            }
                            $value = $newValue;
                        } else {
                            $value = $value->$fromNamePart;
                        }
                    }
                }
                $toNameParts = explode('.', $toName);
                if (count($toNameParts) > 1) {
                    foreach (array_reverse($toNameParts) as $key => $toNamePart) {
                        if ($key === 0 && is_array($value)) {
                            $value = array_map(function ($item) use ($toNamePart) {
                                return [$toNamePart => $item];
                            }, $value);
                        } else {
                            $value = [$toNamePart => $value];
                        }
                    }
                    reset($value);
                    $toName = key($value);
                    $value = reset($value);
                }
            }
            if ($value instanceof Value) {
                $value = $value->value;
            } elseif ($value instanceof ActiveQuery) {
                //$value = $value->multiple ? $value->all() : $value->one();
                $value = null;
            }
            if ($value !== null) {
                if ($attribute->type) {
                    settype($value, $attribute->type);
                }
                if ($document->has($toName) && is_array($document->get($toName))) {
                    $document->set($toName, array_replace_recursive($document->get($toName), $value));
                } else {
                    $document->set($toName, $value);
                }
            }
        }

        return $document;
    }
}