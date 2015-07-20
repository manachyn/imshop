<?php

namespace im\catalog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductTypeSearch represents the model behind the search form about `im\catalog\models\ProductType`.
 */
class ProductTypeSearch extends ProductType
{
    public $parentName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parentName'], 'safe']
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProductType::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['parentName'] = [
            'asc' => ['parent.name' => SORT_ASC],
            'desc' => ['parent.name' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            $dataProvider->getSort() !== false ? $query->joinWith(['parentRelation']) : $query->with('parentRelation');
            return $dataProvider;
        }
        else {
            $query->joinWith(['parentRelation']);
        }

        $query->andFilterWhere(['like', '{{%product_types}}.name', $this->name]);
        $query->andFilterWhere(['like', 'parent.name', $this->parentName]);

        return $dataProvider;
    }
}
