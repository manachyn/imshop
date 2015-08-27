<?php

namespace im\elasticsearch\components;

use im\search\components\finder\FinderInterface;
use im\search\components\index\IndexerInterface;
use im\search\components\service\BaseSearchService;
use Yii;

class SearchService extends BaseSearchService
{
    private $_indexer = 'im\elasticsearch\components\index\Indexer';

    private $_finder = 'im\elasticsearch\components\Finder';

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

    /**
     * Sets service finder instance or config.
     *
     * @param string|array|FinderInterface $finder
     */
    public function setFinder($finder)
    {
        $this->_finder = $finder;
    }

    /**
     * @inheritdoc
     */
    public function getIndexer()
    {
        if (!$this->_indexer instanceof IndexerInterface) {
            $this->_indexer = Yii::createObject($this->_indexer);
        }

        return $this->_indexer;
    }

    /**
     * Sets service indexer instance or config.
     *
     * @param string|array|IndexerInterface $indexer
     */
    public function setIndexer($indexer)
    {
        $this->_indexer = $indexer;
    }


}