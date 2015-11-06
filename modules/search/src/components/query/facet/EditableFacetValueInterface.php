<?php

namespace im\search\components\query\facet;

interface EditableFacetValueInterface
{
    /**
     * The name of the facet value edit view
     * @return string
     */
    public function getEditView();
}