<?php

namespace im\base\actions;

use Yii;
use yii\base\Action;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class ModelAction
 * @package im\base\actions
 */
abstract class ModelAction extends Action
{
    /**
     * @var string class name of the model which will be handled by this action.
     * The model class must implement [[ActiveRecordInterface]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * Runs action.
     * @return mixed
     */
    abstract public function run();

    /**
     * Creates new model.
     * @return ActiveRecord
     */
    protected function createModel()
    {
        /* @var $model ActiveRecord */
        $model = new $this->modelClass(['scenario' => $this->scenario]);
        return $model;
    }

    /**
     * Populates the model with the data from end user.
     * @param Model $model
     * @param array $data
     * @return boolean whether the model is successfully populated with some data.
     */
    protected function loadModel($model, $data)
    {
        return $model->load($data);
    }

    /**
     * Performs the data validation.
     * @param Model $model
     * @return boolean whether the validation is successful without any error.
     */
    protected function validateModel($model)
    {
        return $model->validate();
    }

    /**
     * Saves the model.
     * @param ActiveRecord $model
     * @return boolean whether the saving succeeds
     */
    protected function saveModel($model)
    {
        return $model->save();
    }
}
