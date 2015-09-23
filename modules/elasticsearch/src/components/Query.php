<?php

namespace im\elasticsearch\components;

use im\search\components\query\FacetInterface;
use im\search\components\query\IntervalFacetInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\RangeFacetInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;

class Query extends \yii\elasticsearch\Query implements QueryInterface
{
    /**
     * @var DocumentToObjectTransformerInterface
     */
    private $_transformer;

    /**
     * @var FacetInterface[]
     */
    private $_facets;

    /**
     * @inheritdoc
     */
    public function result($db = null)
    {
        $response = $this->createCommand($db)->search();

        return new QueryResult($this, $response);
    }

    /**
     * @inheritdoc
     */
    public function addFacet(FacetInterface $facet)
    {
        $this->_facets[$facet->getName()] = $facet;
        if ($facet instanceof RangeFacetInterface) {
            $this->addRangeFacet($facet);
        } elseif ($facet instanceof IntervalFacetInterface) {
            $this->addIntervalFacet($facet);
        } else {
            $this->addTermsFacet($facet);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /**
     * @inheritdoc
     */
    public function getTransformer()
    {
        return $this->_transformer;
    }

    /**
     * @inheritdoc
     */
    public function setTransformer(DocumentToObjectTransformerInterface $transformer)
    {
        $this->_transformer = $transformer;
    }

    protected function addRangeFacet(RangeFacetInterface $facet)
    {
        $ranges = $facet->getRanges();
        if ($ranges) {
            $options = ['field' => $facet->getField(), 'ranges' => []];
            foreach ($ranges as $range) {
                $optionsRange = [];
                if ($from = $range->getFrom()) {
                    $optionsRange['from'] = $from;
                }
                if ($to = $range->getTo()) {
                    $optionsRange['to'] = $to;
                }
                if ($optionsRange) {
                    $optionsRange['key'] = $range->getKey();
                    $options['ranges'][] = $optionsRange;
                }
            }
            if ($options['ranges']) {
                $this->addAggregation($facet->getName(), 'range', $options);
            }
        }
    }

    protected function addIntervalFacet(IntervalFacetInterface $facet)
    {
        $options = ['field' => $facet->getField(), 'interval' => $facet->getInterval(), 'min_doc_count' => 0, 'extended_bounds' => []];
//        if ($from = $facet->getFrom()) {
//            $options['extended_bounds']['min'] = 1000;
//        }
//        if ($to = $facet->getTo()) {
//            //$options['extended_bounds']['max'] = 50;
//        }
        if (!$options['extended_bounds']) {
            unset($options['extended_bounds']);
        }
        $this->addAggregation($facet->getName(), 'histogram', $options);
    }

    protected function addTermsFacet(FacetInterface $facet)
    {
        $options = ['field' => $facet->getField()];
        $this->addAggregation($facet->getName(), 'terms', $options);
    }
}