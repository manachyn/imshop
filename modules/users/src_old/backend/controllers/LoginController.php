<?php

namespace app\modules\users\backend\controllers;

use app\modules\backend\components\Controller;
use app\modules\users\models\LoginForm;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use Yii;

class LoginController extends Controller
{

    /**
     * @inheritdoc
     */
    public $defaultAction = 'login';

    /**
     * @inheritdoc
     */
    public $layout = '//login';

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
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

//    /**
//     * Login user.
//     */
//    public function actionLogin()
//    {
//        if (!Yii::$app->user->isGuest) {
//            $this->goHome();
//        }
//
//        $model = new LoginForm();
//
//        if ($model->load(Yii::$app->request->post())) {
//            if ($model->validate()) {
//                if ($model->login()) {
//                    return $this->goHome();
//                }
//            } elseif (Yii::$app->request->isAjax) {
//                Yii::$app->response->format = Response::FORMAT_JSON;
//                return ActiveForm::validate($model);
//            }
//        }
//
//        return $this->render(
//            'login',
//            [
//                'model' => $model
//            ]
//        );
//    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
}
