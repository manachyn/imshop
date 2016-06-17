<?php

namespace im\blog\models;

use yii\db\ActiveQuery;

/**
 * Class ArticleQuery
 * @package im\blog\models
 */
class ArticleQuery extends ActiveQuery
{
    /**
     * @param string $status
     *
     * @return $this
     */
    public function status($status)
    {
        $this->andWhere(['status' => $status]);

        return $this;
    }

    /**
     * @return $this
     */
    public function published()
    {
        $this->andWhere(['status' => Article::STATUS_PUBLISHED]);

        return $this;
    }

    /**
     * @return $this
     */
    public function unpublished()
    {
        $this->andWhere(['status' => Article::STATUS_UNPUBLISHED]);

        return $this;
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        $this->andWhere(['status' => Article::STATUS_DELETED]);

        return $this;
    }
}
