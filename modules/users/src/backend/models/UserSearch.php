<?php

namespace im\users\backend\models;

use im\users\traits\ModuleTrait;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var int
     */
    public $status;

//    /**
//     * @var int
//     */
//    public $created_at;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'first_name', 'last_name', 'created_at'], 'safe'],
            //['created_at', 'default', 'value' => null],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'created_at' => Yii::t('user', 'Registration time')
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        /** @var User $user */
        $user = Yii::createObject($this->module->backendUserModel);
        $query = $user::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $sortAttributes = [
            'first_name' => 'profile.first_name',
            'last_name' => 'profile.last_name'
        ];
        foreach ($sortAttributes as $key => $attribute) {
            $dataProvider->sort->attributes[$key] = [
                'asc' => [$attribute => SORT_ASC],
                'desc' => [$attribute => SORT_DESC]
            ];
        }

        if (!($this->load($params) && $this->validate())) {
            $dataProvider->getSort() !== false ? $query->joinWith(['profile']) : $query->with('profile');
            return $dataProvider;
        }
        else {
            $query->joinWith(['profile']);
        }

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'profile.first_name', $this->first_name])
            ->andFilterWhere(['like', 'profile.last_name', $this->last_name])
            ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }
}
