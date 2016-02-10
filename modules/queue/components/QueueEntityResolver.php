<?php

namespace app\modules\queue\components;

use app\modules\queue\components\exceptions\EntityNotFoundException;
use app\modules\queue\components\interfaces\EntityResolverInterface;
use yii\db\ActiveRecord;

class QueueEntityResolver implements EntityResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve($type, $id)
    {
        $instance = null;

        if (is_subclass_of($type, ActiveRecord::className())) {
            $instance = (new $type)->find($id)->one();
        }

        if ($instance) {
            return $instance;
        }

        throw new EntityNotFoundException($type, $id);
    }
}
