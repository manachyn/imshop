<?php

namespace im\tree\controllers\rest;

use im\tree\models\Tree;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class SearchAction extends IndexAction
{
    /**
     * @var array searchable attributes
     */
    public $searchableAttributes;

    /**
     * @inheritdoc
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass Tree */
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        $data = \Yii::$app->getRequest()->getBodyParams();
        $string = isset($data['string']) ? $data['string'] : '';
        if ($this->searchableAttributes) {
            $condition = ['or'];
            foreach ($this->searchableAttributes as $attribute) {
                $condition[] = ['like', $attribute, $string];
            }
            $query->andWhere($condition);
            /** @var $models Tree[] */
            $models = $query->all();
            foreach ($models as $model) {
                $query->union($model->parents());
            }
        }
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
} 