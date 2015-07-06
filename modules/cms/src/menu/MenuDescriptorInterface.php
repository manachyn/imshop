<?php

namespace im\cms\menu;

interface MenuDescriptorInterface {

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getItems();


} 