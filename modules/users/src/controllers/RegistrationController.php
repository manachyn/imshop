<?php

namespace im\users\controllers;

use im\users\models\Profile;
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
                    ['allow' => true, 'actions' => ['register', 'success', 'connect'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend'], 'roles' => ['?', '@']]
                ]
            ],
        ];
    }

    /**
     * Displays the registration page.
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
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

            $user->profile = $profile;

            if ($user->register()) {
                Yii::$app->session->setFlash('registration.success', Module::t('module', 'Your account has been created.'));

                if (!$module->registrationConfirmation && $module->loginAfterRegistration) {
                    $user->login();
                }

                if ($module->redirectAfterRegistration) {
                    return $this->redirect($module->redirectAfterRegistration);
                } else {
                    return $this->refresh();
                }
            } elseif ($user->hasErrors() || $profile->hasErrors()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($user, $profile);
                }
            } else {
                Yii::$app->session->setFlash('registration.error', Module::t('module', 'An error occurred during registration.'));
            }
        }

        return $this->render('register', [
            'module' => $module,
            'user' => $user,
            'profile' => $profile
        ]);
    }

    /**
     * Displays the success page after registration.
     *
     * @return mixed
     */
    public function actionSuccess()
    {
        return $this->render('success');
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     * @param  integer $id
     * @param  string  $code
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = $this->finder->findUserById($id);

        if ($user === null || $this->module->enableConfirmation == false) {
            throw new NotFoundHttpException;
        }

        $user->attemptConfirmation($code);

        return $this->render('/message', [
            'title'  => \Yii::t('user', 'Account confirmation'),
            'module' => $this->module,
        ]);
    }
}
