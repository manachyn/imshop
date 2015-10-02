<?php

namespace im\search\components\query\parser\entry;

/**
 * Range query entry.
 *
 * @package im\search\components\query\parser\entry
 */
class Range implements QueryEntryInterface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var mixed
     */
    private $_from;

    /**
     * @var mixed
     */
    private $_to;

    /**
     * @var bool
     */
    private $_fromInclude = true;

    /**
     * @var bool
     */
    private $_toInclude = true;

    /**
     * @param string $field
     * @param mixed $from
     * @param mixed $to
     * @param bool $fromInclude
     * @param bool $toInclude
     */
    function __construct($field, $from, $to, $fromInclude, $toInclude)
    {
        $this->_field = $field;
        $this->_from = $from;
        $this->_to = $to;
        $this->_fromInclude = $fromInclude;
        $this->_toInclude = $toInclude;
    }
}