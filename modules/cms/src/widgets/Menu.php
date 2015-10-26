<?php

namespace im\cms\widgets;

use im\cms\models\MenuItem;
use im\tree\components\TreeHelper;
use yii\base\Widget;
use yii\helpers\Html;

class Menu extends Widget
{
    /**
     * @var array the HTML attributes for the widget container tag.
     */
    public $options = [];

    /**
     * @var string
     */
    public $itemView = 'menu_item';

    /**
     * @inheritdoc
     */
    public $depth;

    /**
     * @var string this property allows you to customize the HTML which is used to generate the drop down caret symbol.
     */
    public $dropDownCaret;

    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, ['widget' => 'nav']);
        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = Html::tag('span', '', ['class' => 'caret']);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $items = MenuItem::find()->where(['menu_id' => 1, 'status' => MenuItem::STATUS_ACTIVE])->all();
        $items = TreeHelper::buildNodesTree($items);

        return $this->render('menu', [
            'widget' => $this,
            'items' => $items,
            'level' => 1
        ]);
    }
}