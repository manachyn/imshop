<?php

namespace im\blog\frontend\controllers;

use yii\web\Controller;

/**
 * Class ArticleController displays articles on the frontend.
 *
 * @package im\blog\frontend\controllers
 */
class ArticleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'view' => [
                'class' => 'im\cms\components\PageModelViewAction',
                'modelClass' => 'im\blog\models\Article'
            ]
        ];
    }
}
