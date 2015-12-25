<?php

namespace im\cms\models;

use yii\db\ActiveQuery;

class PageQuery extends ActiveQuery
{
    public $type;

    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

    public function status($status)
    {
        $this->andWhere(['status' => $status]);

        return $this;
    }

    public function published()
    {
        $this->andWhere(['status' => Page::STATUS_PUBLISHED]);

        return $this;
    }

    public function unpublished()
    {
        $this->andWhere(['status' => Page::STATUS_UNPUBLISHED]);

        return $this;
    }

    public function deleted()
    {
        $this->andWhere(['status' => Page::STATUS_DELETED]);

        return $this;
    }
}