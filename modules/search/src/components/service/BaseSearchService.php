<?php

namespace im\search\components\service;

use yii\base\Component;

/**
 * Class BaseSearchService
 * @package im\search\components\service
 */
abstract class BaseSearchService extends Component implements SearchServiceInterface
{
    /**
     * @var string
     */
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