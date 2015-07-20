<?php

namespace im\tree\widgets;

use yii\web\AssetBundle;

class JsTreeAsset extends AssetBundle
{
    public $sourcePath = '@bower/jstree/dist';

    public $depends = [
        'yii\web\JqueryAsset',
        'im\tree\widgets\JsTreeApiAsset'
    ];

    /**
     * Set up CSS and JS asset arrays based on the base-file names
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = [])
    {
        $srcFiles = [];
        $minFiles = [];
        foreach ($files as $file) {
            $srcFiles[] = "{$file}.{$type}";
            $minFiles[] = "{$file}.min.{$type}";
        }
        if (empty($this->$type)) {
            $this->$type = YII_ENV_DEV ? $srcFiles : $minFiles;
        }
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setupAssets('css', ['themes/default/style']);
        $this->setupAssets('js', ['jstree']);
        parent::init();
    }
} 