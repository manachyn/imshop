<?php

namespace im\search\components\query\parser\entry;

class Phrase implements QueryEntryInterface
{
    /**
     * @var string
     */
    private $_phrase;

    /**
     * @var string
     */
    private $_field;

    /**
     * @param string $phrase
     * @param string $field
     */
    public function __construct($phrase, $field)
    {
        $this->_phrase = $phrase;
        $this->_field = $field;
    }
}
