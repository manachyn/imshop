<?php

namespace im\image\glide\controllers;

use yii\web\Controller;

class GlideController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'im\image\glide\actions\GlideAction'
            ]
        ];
    }
}
