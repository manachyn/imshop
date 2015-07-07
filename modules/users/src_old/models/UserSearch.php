<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\users\models\User;

/**
 * UserSearch represents the model behind the search form about `app\modules\users\models\User`.
 */
class UserSearch extends User
{
    /**
     * @var string First name
     */
    public $first_name;

    /**
     * @var string Last name
     */
    public $last_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // String
            [['first_name', 'last_name', 'username', 'email'], 'string'],
            // Role
            ['role', 'in', 'range' => array_keys(self::getRolesList())],
            // Status
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            // Date
            [['created_at', 'updated_at'], 'date', 'format' => 'd.m.Y']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        //$query = User::find();
        $query = self::find()->joinWith(['profile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['first_name'] = [
            'asc' => [Profile::tableName() . '.first_name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.first_name' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['last_name'] = [
            'asc' => [Profile::tableName() . '.last_name' => SORT_ASC],
            'desc' => [Profile::tableName() . '.last_name' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'role' => $this->role,
            'FROM_UNIXTIME(created_at, "%d.%m.%Y")' => $this->created_at,
            'FROM_UNIXTIME(updated_at, "%d.%m.%Y")' => $this->updated_at
        ]);

        $query->andFilterWhere(['like', Profile::tableName() . '.first_name', $this->first_name]);
        $query->andFilterWhere(['like', Profile::tableName() . '.last_name', $this->last_name]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
