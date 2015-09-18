<?php

namespace im\catalog\controllers;

use im\catalog\components\CategoryContextInterface;
use im\catalog\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CategoryController extends Controller implements CategoryContextInterface
{
    /**
     * @var Category
     */
    protected $category;

    public function actionView($path)
    {
        $this->category = $model = $this->findModel($path);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Finds the Category model based on its path.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $path
     * @throws \yii\web\NotFoundHttpException
     * @return Category the loaded model
     */
    protected function findModel($path)
    {
        if (($model = Category::findByPath($path)->active()->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}