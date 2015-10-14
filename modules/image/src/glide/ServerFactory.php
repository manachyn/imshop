<?php

namespace im\image\glide;

use Yii;

class ServerFactory extends \League\Glide\ServerFactory
{
    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
        if (isset($this->config['source']) && is_string($this->config['source'])) {
            $this->config['source'] = Yii::getAlias($this->config['source']);
        }
        if (isset($this->config['cache']) && is_string($this->config['cache'])) {
            $this->config['cache'] = Yii::getAlias($this->config['cache']);
        }
        if (isset($this->config['watermarks']) && is_string($this->config['watermarks'])) {
            $this->config['watermarks'] = Yii::getAlias($this->config['watermarks']);
        }
        if (!isset($this->config['driver'])) {
            $this->config['driver'] = extension_loaded('imagick') ? 'imagick' : 'gd';
        }
    }
} 