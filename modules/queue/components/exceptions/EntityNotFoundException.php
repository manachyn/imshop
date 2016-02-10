<?php

namespace app\modules\queue\components\exceptions;

use yii\base\InvalidParamException;

class EntityNotFoundException extends InvalidParamException
{
    /**
     * Create a new exception instance.
     *
     * @param  string $type
     * @param  mixed $id
     * @return EntityNotFoundException
     */
    public function __construct($type, $id)
    {
        $id = (string) $id;

        parent::__construct("Queueable entity [{$type}] not found for ID [{$id}].");
    }
}
