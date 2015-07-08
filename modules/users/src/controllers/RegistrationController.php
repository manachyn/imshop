<?php

namespace im\users\controllers;
use im\users\models\Profile;
use im\users\models\RegistrationForm;
use im\users\models\User;
use im\users\Module;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

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
        /** @var User $user */
        $user = Yii::createObject($module->userModel);
        /** @var Profile $profile */
        $profile = Yii::createObject($module->profileModel);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            $user->registration_ip = Yii::$app->request->userIP;
            if ($user->validate() && $profile->validate()) {
                if ($user->save() && $profile->save()) {
                    return $this->render('success', [
                        'user' => $user,
                        'profile' => $profile
                    ]);
                }
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($user, $profile);
            }
        }

        return $this->render('register', [
            'module' => $module,
            'user' => $user,
            'profile' => $profile
        ]);
    }
}
