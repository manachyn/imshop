<?php

namespace im\elasticsearch\components;

use im\search\components\index\Document;
use im\search\components\service\db\ActiveRecordToDocumentTransformer;

/**
 * Class ActiveRecordToElasticDocumentTransformer
 * @package im\search\components
 */
class ActiveRecordToElasticDocumentTransformer extends ActiveRecordToDocumentTransformer
{
    /**
     * @inheritdoc
     */
    protected function postTransform(Document $document, $object, $attributes)
    {
        foreach ($attributes as $name => $attribute) {
            if (!empty($attribute->params['suggestions']) && $document->has($attribute->name)
                && ($value = $document->get($attribute->name)) && is_string($value)) {
                if ($inputs = $this->getSuggestionInputs($value)) {
                    $document->set($attribute->name . '_suggest', count($inputs) > 1 ? ['input' => $inputs] : $value);
                }
            }
        }

        return $document;
    }

    /**
     * @param string $text
     * @return array
     */
    private function getSuggestionInputs($text)
    {
        $words = explode(' ', $text);
        $count = count($words);
        $inputs = [];
        for ($i = 0; $i < $count; $i++) {
            $inputs[] = implode(' ', array_slice($words, $i));
        }

        return $inputs;
    }
}