<?php

namespace im\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ArticleSearch represents the model behind the search form about `im\blog\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @var int
     */
    public $category_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'title', 'slug', 'status', 'created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->getQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }

    /**
     * @return ArticleQuery
     */
    protected function getQuery()
    {
        $query = Article::find();
        if ($this->category_id) {
            $query->innerJoin(
                '{{%articles_categories}}',
                '{{%articles}}.id = {{%articles_categories}}.article_id AND {{%articles_categories}}.category_id = :category_id', ['category_id' => $this->category_id]);
        }

        return $query;
    }
}
