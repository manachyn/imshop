<?php

namespace im\cms\menu;

use yii\base\Object;

class MenuDescriptor extends Object implements MenuDescriptorInterface {

    /** @var string */
    private $_id;

    /** @var string */
    private $_name;

    /** @var array */
    private $_items;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param array $items
     */
    public function setItems($items)
    {
        $this->_items = $items;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    //    /**
//     * @var MenuProviderInterface|array|string the provider object or the configuration for creation the provider object.
//     */
//    private $provider;



}