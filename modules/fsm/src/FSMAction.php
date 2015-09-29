<?php

namespace im\fsm;

class FSMAction
{
    /**
     * Object reference
     *
     * @var object
     */
    private $_object;

    /**
     * Method name
     *
     * @var string
     */
    private $_method;

    /**
     * Object constructor
     *
     * @param object $object
     * @param string $method
     */
    public function __construct($object, $method)
    {
        $this->_object = $object;
        $this->_method = $method;
    }

    public function doAction()
    {
        $methodName = $this->_method;
        $this->_object->$methodName();
    }
}
