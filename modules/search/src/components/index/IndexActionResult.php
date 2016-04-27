<?php

namespace im\search\components\index;

/**
 * Class IndexActionResult
 * @package im\search\components\index
 */
class IndexActionResult
{
    /**
     * @var string
     */
    public $action;

    /**
     * @var int total documents count
     */
    public $total = 0;

    /**
     * @var int successfully processed documents count
     */
    public $success = 0;

    /**
     * @var int processed with error documents count
     */
    public $error = 0;

    /**
     * @var int not founded documents count
     */
    public $notFounded = 0;

    /**
     * @param $action
     */
    function __construct($action)
    {
        $this->action = $action;
    }

    public function add(IndexActionResult $result)
    {
        $this->success += $result->success;
        $this->error += $result->error;
        $this->notFounded += $result->notFounded;
    }
}