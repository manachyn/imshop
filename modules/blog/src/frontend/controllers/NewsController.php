<?php

namespace im\blog\frontend\controllers;

use yii\web\Controller;

/**
 * Class NewsController displays news on the frontend.
 *
 * @package im\blog\frontend\controllers
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'view' => [
                'class' => 'im\cms\components\PageModelViewAction',
                'modelClass' => 'im\blog\models\News'
            ]
        ];
    }
}
