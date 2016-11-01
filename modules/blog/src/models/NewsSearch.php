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
        $query = News::find();
        if ($this->category_id) {
            $query->innerJoin(
                '{{%news_categories_pivot}}',
                '{{%news}}.id = {{%news_categories_pivot}}.news_id AND {{%news_categories_pivot}}.category_id = :category_id', ['category_id' => $this->category_id]);
        }

        return $query;
    }
}
