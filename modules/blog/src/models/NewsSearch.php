<?php

namespace im\blog\models;

/**
 * NewsSearch represents the model behind the search form about `im\blog\models\News`.
 */
class NewsSearch extends ArticleSearch
{
    /**
     * @inheritdoc
     */
    protected function getQuery()
    {
        return News::find();
    }
}
