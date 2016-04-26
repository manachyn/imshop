<?php

namespace im\forms\components;

class ContentBlock implements SetItemInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var SetInterface
     */
    protected $parent;

    function __construct($name, $content = '')
    {
        $this->name = $name;
        $this->content = $content;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function render($params = [])
    {
        return $this->content;
    }
}