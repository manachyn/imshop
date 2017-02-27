<?php

namespace im\base\controllers;

use Yii;
use yii\base\Theme;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Base class for backend controllers.
 * @package im\base\controllers
 */
class BackendController extends Controller
{
    /**
     * @var Theme|array|string the theme object or the configuration for creation the theme object.
     * If not set, it means theming is not enabled.
     */
    public $backendTheme;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $backendTheme = Yii::$app->get('backendTheme', false);
        if ($backendTheme && $backendTheme instanceof Theme) {
            $this->getView()->theme = $backendTheme;
        }
    }
}

