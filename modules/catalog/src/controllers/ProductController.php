<?php

namespace im\catalog\controllers;

use im\catalog\models\Product;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * Displays product.
     *
     * @param string $path
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($path)
    {
        $model = $this->findModel($path);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Finds the model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path
     * @throws \yii\web\NotFoundHttpException
     * @return Product the loaded model
     */
    protected function findModel($path)
    {
        /** @var Product $model */
        $model = Yii::createObject(Product::className());
        if (($model = $model::findByPath($path)->active()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}