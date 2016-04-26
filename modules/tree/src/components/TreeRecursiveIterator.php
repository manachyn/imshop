<?php

namespace im\tree\components;

class TreeRecursiveIterator implements \RecursiveIterator
{
    /**
     * @var array
     */
    private $_nodes;

    /**
     * @var int
     */
    private $_position = 0;

    public function __construct(array $nodes)
    {
        $this->_nodes = $nodes;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->_nodes[$this->_position]['node'];
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->_position++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->_position;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return isset($this->_nodes[$this->_position]);
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->_position = 0;
    }

    /**
     * @inheritdoc
     */
    public function hasChildren()
    {
        return isset($this->_nodes[$this->_position]['children']);
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        $this->_nodes[$this->_position]['children'];
    }
}