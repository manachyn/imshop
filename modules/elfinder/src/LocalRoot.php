<?php

namespace im\elfinder;

use Yii;

class LocalRoot extends Root
{
    /**
     * @var string volume driver
     */
    public $driver = 'LocalFileSystem';

    /**
     * @var string root directory path
     */
    public $path;

    /**
     * @var string URL that points to path directory (also called 'root URL')
     */
    public $url;

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//       // $this->url = $this->url ?: \Yii::getAlias()
//        //$this->path = \Yii::getAlias()
//    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), [
            'path' => Yii::getAlias($this->path),
            'URL' => Yii::getAlias($this->url)
        ]);
    }
}