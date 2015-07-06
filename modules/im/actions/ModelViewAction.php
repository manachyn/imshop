<?php

namespace im\base\actions;

use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\NotFoundHttpException;

/**
 * Class ModelViewAction
 * @package im\base\actions
 */
class ModelViewAction extends Action
{
    /**
     * @var string class name of the model which will be handled by this action.
     * The model class must implement [[ActiveRecordInterface]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var callable a PHP callable that will be called to return the model corresponding
     * to the specified primary key value. If not set, [[findModel()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($id, $action) {
     *     // $id is the primary key value. If composite primary key, the key values
     *     // will be separated by comma.
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return the model found, or throw an exception if not found.
     */
    public $findModel;

    /**
     * @var string the name of the view.
     * Defaults to 'index'. This should be in the format of 'path/to/view'
     */
    public $view = 'view';

    /**
     * @var mixed the name of the layout to be applied to the requested view.
     * This will be assigned to [[\yii\base\Controller::$layout]] before the view is rendered.
     * Defaults to null, meaning the controller's layout will be used.
     * If false, no layout will be applied.
     */
    public $layout;


    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException(get_class($this) . '::$modelClass must be set.');
        }

        if (!is_string($this->view) || !preg_match('/^\w[\w\/\-\.]*$/', $this->view)) {
            throw new InvalidConfigException("View \"$this->view\" must start with a word character and can contain only word characters, forward slashes, dots and dashes.");
        }
    }

    /**
     * Displays a model.
     * @param string $id the primary key of the model.
     * @return string
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }

        $output = $this->render($this->view, $model);

        return $output;
    }

    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return ActiveRecordInterface the model found
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode(',', $id);
            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            $model = $modelClass::findOne($id);
        }

        if (isset($model)) {
            return $model;
        } else {
            throw new NotFoundHttpException("Model not found: $id");
        }
    }

    /**
     * Renders a view
     *
     * @param string $viewName view name
     * @param ActiveRecordInterface $model model for render
     * @return string result of the rendering
     */
    protected function render($viewName, $model)
    {
        return $this->controller->render($viewName, ['model' => $model]);
    }
} 