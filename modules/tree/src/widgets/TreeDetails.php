<?php

namespace im\tree\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TreeDetails extends Widget
{
    /**
     * @var array the HTML attributes for the container tag of the widget.
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $options = $this->options;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        echo Html::beginTag($tag, $options);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        echo Html::endTag(isset($this->options['tag']) ? $this->options['tag'] : 'div');
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $view = $this->getView();
        TreeDetailsAsset::register($view);
        $view->registerJs("$('#$id').treeDetails()");
    }
} 