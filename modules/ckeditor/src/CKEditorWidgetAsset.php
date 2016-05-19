<?php

namespace im\ckeditor;

use yii\web\AssetBundle;

/**
 * Class CKEditorWidgetAsset
 * @package im\ckeditor
 */
class CKEditorWidgetAsset extends AssetBundle
{
    public $depends = [
        'im\ckeditor\CKEditorAsset'
    ];

    public $js = [
        'dosamigos-ckeditor.widget.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}
