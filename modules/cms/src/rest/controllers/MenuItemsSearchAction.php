<?php

namespace im\cms\rest\controllers;

use im\cms\models\MenuItem;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Action;

class MenuItemsSearchAction extends Action
{
    /**
     * @var array searchable attributes
     */
    public $searchableAttributes;

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     */
    public $prepareDataProvider;

    /**
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider
     */
    public function run($id)
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        return $this->prepareDataProvider($id);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider($id)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        $query = MenuItem::find()->where(['menu_id' => $id]);
        $data = \Yii::$app->getRequest()->getBodyParams();
        $string = isset($data['string']) ? $data['string'] : '';
        if ($this->searchableAttributes) {
            $condition = ['or'];
            foreach ($this->searchableAttributes as $attribute) {
                $condition[] = ['like', $attribute, $string];
            }
            $query->andWhere($condition);
            /** @var $models MenuItem[] */
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
