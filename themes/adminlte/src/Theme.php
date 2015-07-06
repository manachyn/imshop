<?php

namespace im\adminlte;

use Yii;

/**
 * AdminLTE2 theme.
 *
 * @package im\adminlte
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@app/views' => '@app/themes/adminlte/views',
        '@app/modules' => '@app/themes/adminlte/modules'
    ];

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        parent::init();
//
//        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapAsset'] = [
//            'sourcePath' => '@app/themes/admin/assets',
//            'css' => [
//                'css/bootstrap.min.css'
//            ]
//        ];
//        Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'] = [
//            'sourcePath' => '@app/themes/admin/assets',
//            'js' => [
//                'js/bootstrap.min.js'
//            ]
//        ];
//        Yii::$container->set('yii\grid\CheckboxColumn', [
//            'checkboxOptions' => [
//                'class' => 'simple'
//            ]
//        ]);
//    }
}
