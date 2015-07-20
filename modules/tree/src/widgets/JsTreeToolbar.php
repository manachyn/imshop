<?php

namespace im\tree\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class JsTreeToolbar extends Widget
{
    /**
     * @var array the HTML attributes for the container tag.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     */
    public $options = [];

    public $tree;

    public $pjax;

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
        JsTreeToolbarAsset::register($view);
        $options = Json::encode(['tree' => $this->tree, 'pjax' => $this->pjax]);
        $view->registerJs("jQuery('#$id').jsTreeToolbar($options);");
        parent::run();
    }
} 