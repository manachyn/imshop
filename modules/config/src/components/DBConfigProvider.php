<?php

namespace im\config\components;

use im\config\models\Config;
use yii\base\Component;
use Yii;

/**
 * Class DBConfigProvider
 * @package im\config\components
 */
class DBConfigProvider extends Component implements ConfigProviderInterface
{
    /**
     * @var array config cache
     */
    protected $data = array();

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        parent::init();
//        $data  = Yii::$app->cache->get(__CLASS__);
//        if ($data === false) {
//            //TODO to use DAO
//            $items = Config::find()->all();
//            /** @var Config $item */
//            foreach ($items as $item){
//                if ($item->key)
//                    $this->data[$item->key] = $item->value;
//            }
//            Yii::$app->cache->set(__CLASS__, $this->data, 60);
//        }
//        else {
//            $this->data = $data;
//        }
//    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $this->load($key);
        if (is_array($key))
            return array_intersect_key($this->data, array_flip($key));
        else
            return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->load($key);
        $config = $this->has($key) ? Config::findOne(['key' => $key]) : new Config();
        $config->key = $key;
        $config->value = $this->data[$key] = $value;
        $config->save();
        Yii::$app->cache->set([__CLASS__, $key], $value);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $value = $this->get($key);
        unset($this->data[$key]);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    protected function load($key) {
        if (is_array($key))
            foreach ($key as $item) {
                $this->load($item);
            }
        elseif (!isset($this->data[$key])) {
            $data  = Yii::$app->cache->get([__CLASS__, $key]);
            if ($data === false) {
                /** @var Config $config */
                $config = Config::findOne(['key' => $key]);
                if ($config !== null && $config->key) {
                    $this->data[$config->key] = $config->value;
                    Yii::$app->cache->set([__CLASS__, $key], $config->value);
                }
            }
            else {
                $this->data[$key] = $data;
            }
        }
    }
}