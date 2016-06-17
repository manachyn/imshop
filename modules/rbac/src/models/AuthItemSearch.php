<?php

namespace im\rbac\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Query;

/**
 * Class AuthItemSearch
 * @package im\rbac\models
 */
class AuthItemSearch extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $rule_name;

    /**
     * @var \yii\rbac\DbManager
     */
    protected $manager;

    /**
     * @var int
     */
    protected $type;

    /**
     * AuthItemSearch constructor.
     * @param int $type
     * @param array $config
     */
    public function __construct($type, $config = [])
    {
        parent::__construct($config);
        $this->manager = Yii::$app->authManager;
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'default' => ['name', 'description', 'rule_name'],
        ];
    }
    
    /**
     * @param array $params
     * @return ArrayDataProvider
     */
    public function search($params = [])
    {
        $dataProvider = Yii::createObject(ArrayDataProvider::className());
        
        $query = (new Query)->select(['name', 'description', 'rule_name'])
                ->andWhere(['type' => $this->type])
                ->from($this->manager->itemTable);
        
        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'rule_name', $this->rule_name]);
        }
        
        $dataProvider->allModels = $query->all($this->manager->db);
        
        return $dataProvider;
    }
}

