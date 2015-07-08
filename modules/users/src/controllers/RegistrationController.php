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

        /** @var User $userClass */
        $userClass = $module->userModel;

        /** @var User $user */
        $user = Yii::$container->get($userClass, [], ['scenario' => $userClass::SCENARIO_REGISTER]);

        /** @var Profile $profileClass */
        $profileClass = $module->profileModel;

        /** @var Profile $profile */
        $profile = Yii::$container->get($profileClass, [], ['scenario' => $profileClass::SCENARIO_REGISTER]);

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {

            $user->registration_ip = ip2long(Yii::$app->request->userIP);

            if ($user->validate() && $profile->validate()) {
                if ($user->save() && $profile->save()) {

                    Yii::$app->session->setFlash('success', Module::t('module', 'Your account has been created.'));

                    if (!$module->registrationConfirmation && $module->loginAfterRegistration) {
                        Yii::$app->user->login($user);
                    }

                    if ($module->redirectAfterRegistration) {
                        $this->redirect($module->redirectAfterRegistration);
                    } else {
                        return $this->refresh();
                    }

                } else {
                    Yii::$app->session->setFlash('danger', Module::t('module', 'An error occurred during registration.'));
                    return $this->refresh();
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
