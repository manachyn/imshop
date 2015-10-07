<?php

namespace im\search\components\query;

/**
 * Phrase query.
 *
 * @package im\search\components\query
 */
class Phrase extends Query implements FieldQueryInterface
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
     * Creates phrase query.
     *
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
    public function getField()
    {
        return $this->_field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @return string
     */
    public function getPhrase()
    {
        return $this->_phrase;
    }

    /**
     * @param string $phrase
     */
    public function setPhrase($phrase)
    {
        $this->_phrase = $phrase;
    }


} 