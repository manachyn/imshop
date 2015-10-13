<?php

namespace im\search\components\service\db;

use im\search\components\finder\FinderInterface;
use im\search\components\service\BaseSearchService;
use Yii;

class SearchService extends BaseSearchService
{
    /**
     * @var string|array|FinderInterface
     */
    private $_finder = 'im\search\components\service\db\Finder';

    /**
     * @inheritdoc
     */
    public function getFinder()
    {
        if (!$this->_finder instanceof FinderInterface) {
            $this->_finder = Yii::createObject($this->_finder);
        }

        return $this->_finder;
    }
}