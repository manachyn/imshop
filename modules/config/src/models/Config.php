<?php

namespace im\config\models;

use im\config\components\ConfigInterface;
use yii\base\Model;

/**
 * Class Config
 * @package im\config\models
 */
abstract class Config extends Model implements ConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->getAttributes();
    }

    /**
     * @inheritdoc
     */
    public function getUserSpecificOptions()
    {
        return [];
    }
}
