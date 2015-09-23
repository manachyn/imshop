<?php

namespace im\search\components;

class SearchParam
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|array
     */
    public $value;

    function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}