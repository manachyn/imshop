<?php

namespace im\search\components\query;

interface FieldQueryInterface extends SearchQueryInterface
{
    /**
     * Returns query field.
     *
     * @return string
     */
    public function getField();

    /**
     * Returns a value indicating whether the given query is the same as the current one.
     * -1 - not equals
     * 0 - same field
     * 1 - equals
     * @param SearchQueryInterface $query
     * @return int
     */
    public function equals(SearchQueryInterface $query);
}