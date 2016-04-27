<?php

namespace im\elfinder;

use yii\base\Object;
use Yii;

class Root extends Object
{
    /**
     * @var string volume driver
     */
    public $driver;

    /**
     * @var string root path alias for volume root
     */
    public $alias;

    /**
     * @var array root options
     * @see https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options#root-options
     */
    protected $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->alias && is_array($this->alias)) {
            $this->alias = Yii::t('app', $this->alias[1]);
        }
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_merge(['driver' => $this->driver, 'alias' => $this->alias], $this->options);
    }
} 