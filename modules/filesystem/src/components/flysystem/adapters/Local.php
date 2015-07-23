<?php

namespace im\filesystem\components\flysystem\adapters;

use im\filesystem\helpers\FileHelper;
use yii\helpers\Url;
use Yii;

class Local extends \League\Flysystem\Adapter\Local
{
    /**
     * Gets url by file path
     *
     * @param $path
     * @return string
     */
    public function getUrl($path)
    {
        $path = $this->applyPathPrefix($path);
        $relativeToWeb = substr($path, strlen(Yii::getAlias('@webroot')) + 1);
        if ($relativeToWeb) {
            return Url::to('@web/' . $relativeToWeb);
        } else {
            return Url::to(['/filesystem/files', 'path' => $path]);
        }
    }
} 