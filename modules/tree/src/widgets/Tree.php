<?php

namespace im\tree\widgets;

use yii\base\Widget;

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
     * @var int
     */
    public $depth = 1;

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