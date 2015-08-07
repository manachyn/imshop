<?php

namespace im\eav\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Value search model class.
 */
class ValueSearch extends Value
{
    public $attribute;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'attribute'], 'safe'],
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
        $query = Value::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['attribute'] = [
            'asc' => [Attribute::tableName() . '.presentation' => SORT_ASC],
            'desc' => [Attribute::tableName() . '.presentation' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            $dataProvider->getSort() !== false ? $query->joinWith(['eAttribute']) : $query->with('eAttribute');
            return $dataProvider;
        }
        else {
            $query->joinWith(['eAttribute']);
        }

        $query->andFilterWhere(['like', 'value', $this->value]);
        $query->andFilterWhere(['like', Attribute::tableName() . '.presentation', $this->attribute]);

        return $dataProvider;
    }
}
