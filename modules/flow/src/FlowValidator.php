<?php

namespace im\flow;

use im\flow\step\StepInterface;

class FlowValidator implements FlowValidatorInterface
{
    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var string|null
     */
    protected $stepName;

    /**
     * @var callable
     */
    protected $validation;

    /**
     * FlowValidator constructor.
     * @param string $message
     * @param string $stepName
     * @param \Closure|null $validation
     */
    public function __construct($message = null, $stepName = null, \Closure $validation = null)
    {
        $this->message = $message;
        $this->stepName = $stepName;
        $this->validation = $validation;
    }

    /**
     * {@inheritdoc}
     */
    public function setStepName($stepName)
    {
        $this->stepName = $stepName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStepName()
    {
        return $this->stepName;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set validation.
     *
     * @param \Closure $validation
     *
     * @return $this
     */
    public function setValidation(\Closure $validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation.
     *
     * @return callable
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(FlowContextInterface $flowContext)
    {
        return call_user_func($this->validation) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(StepInterface $step)
    {
        if ($this->getStepName()) {
            return $step->proceed($this->getStepName());
        }

        throw new FlowValidatorException(400, $this->getMessage());
    }
}
