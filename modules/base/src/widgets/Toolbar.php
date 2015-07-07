<?php

namespace im\base\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class Toolbar extends Widget
{
    /**
     * @var array the HTML attributes for the container tag.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     */
    public $options = [];

    public $grid;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo "\n" . Html::endTag('div');
        $id = $this->options['id'];
        $view = $this->getView();
        ToolbarAsset::register($view);
        $options = Json::encode(['grid' => $this->grid]);
        $view->registerJs("jQuery('#$id').toolbar($options);");
        parent::run();
    }
} 