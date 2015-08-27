<?php

namespace im\search\components\index;

/**
 * Class BulkResponse
 * @package im\search\components\index
 */
class BulkResponse extends Response
{
    /**
     * @var Response[]
     */
    protected $responses;

    /**
     * @param string $action
     * @param array $response
     * @param Response[] $responses
     */
    function __construct($action, $response, $responses)
    {
        parent::__construct($action, $response);
        $this->responses = $responses;
    }

    /**
     * @return Response[]
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param Response[] $responses
     */
    public function setResponses($responses)
    {
        $this->responses = $responses;
    }

    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        $success = true;
        foreach ($this->getResponses() as $response) {
            if (!$response->isSuccess()) {
                $success = false;
                break;
            }
        }

        return $success;
    }

    /**
     * @inheritdoc
     */
    public function hasError()
    {
        $hasError = false;
        foreach ($this->getResponses() as $response) {
            if ($response->hasError()) {
                $hasError = true;
                break;
            }
        }

        return $hasError;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        $error = '';
        foreach ($this->getResponses() as $response) {
            if ($response->hasError()) {
                $error = $response->getError();
                break;
            }
        }

        return $error;
    }
}