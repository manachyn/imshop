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
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

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
     * @var array the HTML attributes for the tree item tag.
     */
    public $itemOptions = [];

    /**
     * @var \Closure
     */
    public $itemVisibility;

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