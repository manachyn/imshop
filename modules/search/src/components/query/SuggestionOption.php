<?php

namespace im\search\components\query;

/**
 * Class SuggestionOption
 * @package im\search\components\query
 */
class SuggestionOption
{
    /**
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $text;

    /**
     * @var float
     */
    public $score;

    /**
     * SuggestionOption constructor.
     * @param string $field
     * @param string $text
     * @param float $score
     */
    public function __construct($field, $text, $score = 1.0)
    {
        $this->field = $field;
        $this->text = $text;
        $this->score = $score;
    }
}