<?php

namespace im\flow\Step;

/**
 * Class ActionResult
 * @package im\flow\Step
 */
class ActionResult
{
    /**
     * @var string
     */
    private $stepName;

    /**
     * ActionResult constructor.
     * @param string|null $stepName
     */
    public function __construct($stepName = null)
    {
        $this->stepName = $stepName;
    }

    /**
     * @return string|null The name of the next step.
     */
    public function getNextStepName()
    {
        return $this->stepName;
    }
}

