<?php

namespace im\search\components\search;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class SearchComponent extends Component
{
    public function search($type, $params)
    {
        $query = $this->getQuery($type);
    }

    /**
     * Return search query.
     *
     * @param string $type
     * @param \im\search\components\SearchParam[] $params
     * @param \im\search\components\query\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($type, $params = [], $facets = [])
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('search');
        $index = $searchManager->getIndexManager()->getIndexByType($type);
        $finder = $index->getSearchService()->getFinder();
        $query = $finder->find($index->getName(), $type);
        //$query->where(ArrayHelper::map($params, 'name', 'value'));
        $query->query = ['filtered' => ['filter' => ['term' => ['status' => 1]]]];
        if ($facets) {
            foreach ($facets as $facet) {
                $query->addFacet($facet);
            }
        }

        return $query;
    }

    /**
     * @param string $queryParams
     * @return SearchParam[]
     */
    public function parseQueryParams($queryParams)
    {
        $params = [];
        if ($queryParams) {
            $queryParams = is_string($queryParams) ? explode('/', $queryParams) : $queryParams;
            foreach ($queryParams as $name => $value) {
                if (!is_string($name)) {
                    $paramPart = explode('=', $value);
                    $name = $paramPart[0];
                    $value = isset($paramPart[1]) ? $paramPart[1] : '';
                }
                if (!is_array($value)) {
                    $value = explode(';', $value);
                }
                if (count($value) === 1) {
                    $value = reset($value);
                }
                if ($name) {
                    $params[] = new SearchParam($name, $value);
                }
            }
        }

        return $params;
    }
}