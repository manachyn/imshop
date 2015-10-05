<?php

namespace im\search\components\query;

/**
 * Phrase query.
 *
 * @package im\search\components\query
 */
class Phrase extends Query
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
} 