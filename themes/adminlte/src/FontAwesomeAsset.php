<?php

namespace im\adminlte;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package im\adminlte
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/components/font-awesome';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/font-awesome.min.css',
        //'//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
    ];

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        parent::init();
//        $this->publishOptions['beforeCopy'] = function ($from, $to) {
//            $dirname = basename(dirname($from));
//            return $dirname === 'fonts' || $dirname === 'css';
//        };
//    }
}