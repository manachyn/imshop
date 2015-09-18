<?php

namespace im\catalog\controllers;

use im\catalog\models\ProductCategory;
use yii\web\NotFoundHttpException;

class ProductCategoryController extends CategoryController
{
    /**
     * Finds the Category model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path
     * @throws \yii\web\NotFoundHttpException
     * @return ProductCategory the loaded model
     */
    protected function findModel($path)
    {
        if (($model = ProductCategory::findByPath($path)->active()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}