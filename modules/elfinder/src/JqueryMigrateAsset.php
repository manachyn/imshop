<?php

namespace im\elfinder;

use kartik\base\AssetBundle;

class JqueryMigrateAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    //public $sourcePath = '@im/elfinder/assets';
    public $sourcePath = '@app/modules/elfinder/src/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        //'//code.jquery.com/jquery-migrate-1.2.1.js'
        'js/jquery-migrate-1.2.1.js'
    ];
} 