<?php

namespace im\search\components\query\facet;

interface EntityFacetValueInterface extends FacetValueInterface
{
    /**
     * @return mixed
     */
    public function getEntity();

    /**
     * @param mixed $entity
     */
    public function setEntity($entity);
}