<?php

namespace im\tree\widgets;

use yii\base\Widget;

/**
 * Tree widget.
 *
 * @package im\tree\widgets
 */
class Tree extends Widget
{
    /**
     * @var Tree[]
     */
    public $items;

    /**
     * @var string
     */
    public $itemView;

    /**
     * @var array
     */
    public $itemViewParams = [];

    /**
     * @var int
     */
    public $depth;

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('tree', [
            'widget' => $this,
            'items' => $this->items,
            'level' => 1
        ]);
    }
} 