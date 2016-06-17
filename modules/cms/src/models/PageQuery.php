<?php

namespace im\cms\models;

use im\tree\models\TreeQuery;

/**
 * Class PageQuery
 * @package im\cms\models
 * @method PageQuery roots
 * @method PageQuery leaves
 */
class PageQuery extends TreeQuery
{
    /**
     * @var string
     */
    public $type;

    /**
     * @inheritdoc
     */
    public function prepare($builder)
    {
        if ($this->type !== null) {
            $this->andWhere(['type' => $this->type]);
        }
        return parent::prepare($builder);
    }

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
        $this->andWhere(['status' => Page::STATUS_PUBLISHED]);

        return $this;
    }

    /**
     * @return $this
     */
    public function unpublished()
    {
        $this->andWhere(['status' => Page::STATUS_UNPUBLISHED]);

        return $this;
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        $this->andWhere(['status' => Page::STATUS_DELETED]);

        return $this;
    }
}