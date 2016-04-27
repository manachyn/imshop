<?php

namespace im\elasticsearch\components\index;

use im\search\components\index\Response as BaseResponse;

/**
 * Class Response
 * @package im\elasticsearch\components\index
 */
class Response extends BaseResponse
{
    /**
     * @inheritdoc
     */
    public function isSuccess()
    {
        $data = $this->getResponse();
        if (isset($data['status'])) {
            if ($data['status'] >= 200 && $data['status'] <= 300) {
                return true;
            }
            return false;
        }

        if (isset($data['items'])) {
            if (isset($data['errors']) && $data['errors'] === true) {
                return false;
            }

            foreach ($data['items'] as $item) {
                if (isset($item['index']['ok']) && $item['index']['ok'] == false) {
                    return false;
                } elseif (isset($item['index']['status']) && ($item['index']['status'] < 200 || $item['index']['status'] >= 300)) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasError()
    {
        return isset($this->response['error']);
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        $error = '';
        if (isset($response['error'])) {
            $error = $response['error'];
        }

        return $error;
    }
}