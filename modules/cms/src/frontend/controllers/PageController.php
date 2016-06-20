<?php

namespace im\cms\frontend\controllers;

use yii\web\Controller;

/**
 * Class PageController displays page on the frontend.
 *
 * @package im\cms\controllers
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
            'child' => [
                'class' => 'im\cms\components\PageChildViewAction',
                'modelClass' => 'im\cms\models\Page'
            ],
        ];
    }
}
