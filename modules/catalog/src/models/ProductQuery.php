<?php

namespace im\catalog\models;
use yii\db\ActiveQuery;

/**
 * Class ProductQuery
 * @package im\catalog\models
 */
class ProductQuery extends ActiveQuery
{
    public function status($status)
    {
        $this->andWhere(['status' => $status]);

        return $this;
    }

    public function active()
    {
        $this->andWhere(['status' => Product::STATUS_ACTIVE]);

        return $this;
    }

    public function inactive()
    {
        $this->andWhere(['status' => Product::STATUS_INACTIVE]);

        return $this;
    }
}