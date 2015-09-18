<?php

namespace im\search\components;

use Yii;
use yii\base\Component;

class SearchComponent extends Component
{
    public function search($type, $params)
    {
        $query = $this->getQuery($type);
    }

    /**
     * Return search query.
     *
     * @param $type
     * @return query\QueryInterface
     */
    public function getQuery($type)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $index = $searchManager->getIndexManager()->getIndexByType($type);
        $finder = $index->getSearchService()->getFinder();

        return $finder->find($index->getName(), $type);
    }
}