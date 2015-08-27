<?php

namespace im\search\components\service;

use yii\base\Component;

abstract class BaseSearchService extends Component implements SearchServiceInterface
{
    private $_name;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }
}