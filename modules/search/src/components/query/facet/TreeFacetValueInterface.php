<?php

namespace im\search\components\query\facet;

interface TreeFacetValueInterface
{
    public function getChildren();

    public function setChildren($children);
}