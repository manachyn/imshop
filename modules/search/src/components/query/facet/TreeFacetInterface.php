<?php

namespace im\search\components\query\facet;

interface TreeFacetInterface
{
    /**
     * @return TreeFacetValueInterface[]
     */
    public function getValuesTree();
}