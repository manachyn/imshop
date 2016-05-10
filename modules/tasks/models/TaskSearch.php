<?php

namespace app\modules\tasks\models;

use DateInterval;
use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\FormatConverter;

/**
 * TaskSearch represents the model behind the search form about `app\modules\tasks\models\Task`.
 */
class TaskSearch extends Task
{
    /**
     * @var int created from timestamp
     */
    public $createdFrom;

    /**
     * @var int created to timestamp
     */
    public $createdTo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'updated_at'], 'integer'],
            [['type', 'created_at'], 'safe'],
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
        $query = Task::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->created_at) {
            $format = FormatConverter::convertDateIcuToPhp(Yii::$app->formatter->dateFormat);
            $date = DateTime::createFromFormat($format, $this->created_at);
            $date->setTime(0, 0, 0);
            $this->createdFrom = $date->getTimeStamp();
            $date->add(new DateInterval('P1D'));
            $date->sub(new DateInterval('PT1S'));
            $this->createdTo = $date->getTimeStamp();
        }

        $query->andFilterWhere([
            'and',
            ['id' => $this->id],
            ['status' => $this->status],
            ['between', 'created_at', $this->createdFrom, $this->createdTo]
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
