<?php

namespace app\modules\tasks\models;

use yii\db\ActiveQuery;

class TaskQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }

        return parent::prepare($builder);
    }
}