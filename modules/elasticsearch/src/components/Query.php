<?php

namespace im\elasticsearch\components;

use im\search\components\query\QueryInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;

class Query extends \yii\elasticsearch\Query implements QueryInterface
{
    /**
     * @var DocumentToObjectTransformerInterface
     */
    private $_transformer;

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
    public function addFacet($name, $type, $options)
    {
        $this->addAggregation($name, $type, $options);

        return $this;
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
}