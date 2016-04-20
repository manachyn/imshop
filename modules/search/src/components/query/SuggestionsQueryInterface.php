<?php

namespace im\search\components\query;

use yii\db\Connection;

/**
 * Interface SuggestionsQueryInterface
 * @package im\search\components\query
 */
interface SuggestionsQueryInterface
{
    /**
     * Executes the query and returns result object.
     *
     * @param Connection $db the service connection used to execute the query.
     * @return SuggestionsQueryResultInterface
     */
    public function result($db = null);

    /**
     * Sets suggest query.
     *
     * @param Suggest $suggestQuery
     */
    public function setSuggestQuery(Suggest $suggestQuery);

    /**
     * Returns suggest query.
     *
     * @return Suggest
     */
    public function getSuggestQuery();
}