<?php

namespace im\search\components\index;

use yii\base\InvalidParamException;

class Document
{
    /**
     * @var string
     */
    private $_index;

    /**
     * @var string
     */
    private $_type;

    /**
     * @var mixed
     */
    private $_id;

    /**
     * @var float
     */
    private $_relevance;

    /**
     * @var array
     */
    private $_data = array();

    public function __construct($id = '', $data = array(), $type = '', $index = '', $relevance = 0)
    {
        $this->setId($id);
        $this->setData($data);
        $this->setType($type);
        $this->setIndex($index);
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->_index;
    }

    /**
     * @param string $index
     */
    public function setIndex($index)
    {
        $this->_index = $index;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return float
     */
    public function getRelevance()
    {
        return $this->_relevance;
    }

    /**
     * @param float $relevance
     */
    public function setRelevance($relevance)
    {
        $this->_relevance = $relevance;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new InvalidParamException("Field '{$key}' does not exist");
        }

        return $this->_data[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->_data);
    }
}