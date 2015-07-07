<?php

namespace im\users\controllers;
use im\users\models\RegistrationForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class RegistrationController
 *
 * @property \im\users\Module $module
 * @package im\users\controllers
 */
class RegistrationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['register', 'connect'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend'], 'roles' => ['?', '@']]
                ]
            ],
        ];
    }


    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        /** @var RegistrationForm $model */
        $model = \Yii::createObject(RegistrationForm::className());

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
            return $this->render('success', [
                'user' => $model->user
            ]);
        }

        return $this->render('register', [
            'model'  => $model
        ]);
    }
}
