<?php

namespace im\tinymce;

use yii\helpers\Json;

/**
 * Class TinyMCE
 * @package im\tinymce
 */
class TinyMCE extends \dosamigos\tinymce\TinyMce
{
    use TinyMCETrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * @inheritdoc
     */
    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();

        TinyMCEAsset::register($view);

        $id = $this->options['id'];

        $this->clientOptions['selector'] = "#$id";
        // @codeCoverageIgnoreStart
//        if ($this->language !== null) {
//            $langFile = "langs/{$this->language}.js";
//            $langAssetBundle = TinyMceLangAsset::register($view);
//            $langAssetBundle->js[] = $langFile;
//            $this->clientOptions['language_url'] = $langAssetBundle->baseUrl . "/{$langFile}";
//        }
        // @codeCoverageIgnoreEnd

        $options = Json::encode($this->clientOptions);

        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }
        $view->registerJs(implode("\n", $js));
    }
}

