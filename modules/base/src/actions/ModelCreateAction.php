<?php

namespace im\base\actions;

use Yii;

/**
 * Class ModelCreateAction
 * @package im\base\actions
 */
class ModelCreateAction extends ModelAction
{
    /**
     * @var string the name of the view action. This property is need to create the URL when the model is successfully created.
     */
    public $viewAction = 'view';

    /**
     * Creates a new model.
     * @return mixed
     */
    public function run()
    {
        $model = $this->createModel();
        if ($this->loadModel($model, Yii::$app->request->post())) {
            if ($this->validateModel($model)) {
                if ($this->saveModel($model)) {

                }
            }
        }
    }
}
