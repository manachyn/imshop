<?php

namespace im\catalog\rest\controllers;

use im\catalog\models\ProductType;
use im\eav\models\Attribute;
use yii\db\ActiveRecordInterface;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class ProductTypeController extends ActiveController
{
    public $modelClass = 'im\catalog\models\ProductType';

    /**
     * Displays related attributes of a model.
     * @param string $id the primary key of the model.
     * @return Attribute[]
     */
    public function actionAttributes($id)
    {
        /** @var ProductType $model */
        $model = $this->findModel($id);
        return $model->getEAttributes();
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
            throw new NotFoundHttpException("Object not found: $id");
        }
    }
} 