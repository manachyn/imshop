<?php

namespace im\search\components\index;

abstract class Response
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var array
     */
    protected $response;

    /**
     * @param string $action
     * @param array $response
     */
    function __construct($action, $response)
    {
        $this->action = $action;
        $this->response = $response;
    }

    /**
     * Returns response action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets response action.
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Returns response data.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets response data.
     *
     * @param array $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Checks whether response is successful.
     *
     * @return bool
     */
    abstract public function isSuccess();

    /**
     * Checks whether response has error.
     *
     * @return bool whether response has error
     */
    abstract public function hasError();

    /**
     * Returns response error.
     *
     * @return string
     */
    abstract public function getError();
}