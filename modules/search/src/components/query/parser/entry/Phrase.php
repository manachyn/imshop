<?php

namespace im\search\components\query\parser\entry;

/**
 * Phrase query entry.
 *
 * @package im\search\components\query\parser\entry
 */
class Phrase implements QueryEntryInterface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var string
     */
    private $_phrase;

    /**
     * @param string $phrase
     * @param string $field
     */
    public function __construct($field, $phrase)
    {
        $this->_field = $field;
        $this->_phrase = $phrase;
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return new \im\search\components\query\Phrase($this->_field, $this->_phrase);
    }
}
