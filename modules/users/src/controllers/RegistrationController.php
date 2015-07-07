<?php

namespace im\users\controllers;
use im\users\models\RegistrationForm;
use im\users\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

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

        /** @var Module $module */
        $module = Yii::$app->getModule('users');
        $user = Yii::createObject($module->userModel);
        $profile = Yii::createObject($module->profileModel);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($user->save() && $profile->save()) {
                return $this->render('success', [
                    'user' => $user,
                    'profile' => $profile
                ]);
            }
        }

        return $this->render('register', [
            'module' => $module,
            'user' => $user,
            'profile' => $profile
        ]);
    }
}
