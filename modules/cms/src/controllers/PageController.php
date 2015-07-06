<?php

namespace im\cms\controllers;

use im\cms\components\layout\Layout;
use im\cms\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'view' => [
                'class' => 'im\cms\components\PageViewAction',
                'modelClass' => 'im\cms\models\Page'
            ],
        ];
    }

//    /**
//     * @inheritdoc
//     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => 'app\modules\base\filters\ActionCache',
//                'only' => ['view'],
//                'duration' => 10,
////                'variations' => [
////                    \Yii::$app->language,
////                ],
////                'dependency' => [
////                    'class' => 'yii\caching\DbDependency',
////                    'sql' => 'SELECT MAX(updated_at) FROM tbl_pages',
////                ],
//            ],
//        ];
//    }

//    /**
//     * Displays a single Page model.
//     * @param string $path
//     * @return mixed
//     */
//    public function actionView($path = 'index')
//    {
//        //sleep(2);
//        $model = $this->findModel($path);
//        if ($model->hasProperty('useLayout')) {
//            /** @var Layout $layout */
//            $layout = $model->getLayout(false);
//            if ($layout !== null)
//                $this->layout = '//' . $layout->id;
//        }
//        return $this->render('view', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Finds the Page model based on its path.
//     * If the model is not found, a 404 HTTP exception will be thrown.
//     * @param $path
//     * @throws \yii\web\NotFoundHttpException
//     * @return Page the loaded model
//     */
//    protected function findModel($path)
//    {
//        if (($model = Page::findByPath($path)->published()->one()) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
}
