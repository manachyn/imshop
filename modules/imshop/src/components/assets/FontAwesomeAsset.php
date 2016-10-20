<?php

namespace im\imshop\components\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package im\imshop\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
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