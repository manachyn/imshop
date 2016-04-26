<?php

namespace im\tree\controllers\rest;

use im\tree\models\Tree;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\IndexAction;

class LeavesAction extends IndexAction
{
    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     */
    public $prepareDataProvider;

    /**
     * @param string $id the primary key of the model.
     * @return ActiveDataProvider
     */
    public function run($id = null)
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
    protected function prepareDataProvider($id = null)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }
        if ($id) {
            /** @var $model Tree */
            $model = $this->findModel($id);
            $provider = new ActiveDataProvider(['query' => $model->leaves()]);
        } else {
            /* @var $modelClass Tree */
            $modelClass = $this->modelClass;
            $provider = new ActiveDataProvider(['query' => $modelClass::find()->leaves()]);
        }

        return $provider;
    }
}
