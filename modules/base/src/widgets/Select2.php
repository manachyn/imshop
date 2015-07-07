<?php

namespace im\base\widgets;

use yii\helpers\Html;
use yii\widgets\InputWidget;

class Select2 extends InputWidget
{
    public $items = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            return Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        } else {
            return Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        }
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $selector = '#' . $this->options['id'];
        $settings = Json::encode($this->settings);

        // Register asset
        $asset = Asset::register($view);

        if ($this->language !== null) {
            $asset->language = $this->language;
        }

        if ($this->bootstrap === true) {
            BootstrapAsset::register($view);
        } else {
            Select2Asset::register($view);
        }

        // Init widget
        $view->registerJs("jQuery('$selector').select2($settings);", $view::POS_READY, self::INLINE_JS_KEY . $this->options['id']);

        // Register events
        $this->registerEvents();
    }
}