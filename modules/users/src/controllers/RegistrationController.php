<?php

namespace im\users\controllers;

use im\users\components\UserService;
use im\users\models\RegistrationForm;
use im\users\models\ResendForm;
use im\users\models\Token;
use im\users\models\User;
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
                    ['allow' => true, 'actions' => ['register', 'success', 'connect', 'email'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['confirm', 'resend'], 'roles' => ['?', '@']]
                ]
            ],
        ];
    }

    /**
     * Displays the registration page.
     *
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        /** @var UserService $userService */
        $userService = Yii::$app->user;
        /** @var RegistrationForm $model */
        $model = Yii::createObject($this->module->registrationForm);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $userService->register($model)) {
                Yii::$app->session->setFlash('registration.success', Module::t('registration', 'Your account has been created.'));
                if ($this->module->registrationConfirmation) {
                    Yii::$app->session->addFlash('registration.info', Module::t('registration', 'A message has been sent to your e-mail address. It contains a confirmation link that you have to click to complete registration.'));
                } elseif ($this->module->loginAfterRegistration) {
                    $user->login();
                }
                if ($this->module->redirectAfterRegistration) {
                    return $this->redirect($this->module->redirectAfterRegistration);
                } else {
                    return $this->refresh();
                }
            } elseif (!$model->hasErrors()) {
                Yii::$app->session->setFlash('registration.error', Module::t('registration', 'An error occurred during registration.'));
            }
        }

        return $this->render('register', [
            'model' => $model,
            'module' => $this->module
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
     * Confirms user's account.
     *
     * @param string $token
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($token)
    {
        if (!$this->module->registrationConfirmation) {
            throw new NotFoundHttpException;
        }

        /** @var User $userClass */
        $userClass = $this->module->userModel;
        /** @var Token $tokenClass */
        $tokenClass = $this->module->tokenModel;
        /** @var Token $token */
        $token = $tokenClass::findByToken($token, $tokenClass::TYPE_REGISTRATION_CONFIRMATION);
        if ($token) {
            /** @var User $user */
            $user = $userClass::findOne($token->user_id);
            if ($user->confirm()) {
                $token->delete();
                Yii::$app->session->setFlash('confirmation.success', Module::t('registration', 'Thank you! Registration is complete now.'));
                if ($this->module->loginAfterRegistration) {
                    $user->login();
                }
            } else {
                Yii::$app->session->setFlash('confirmation.error', Module::t('registration', 'Something went wrong and your account has not been confirmed.'));
            }
        } else {
            Yii::$app->session->setFlash('confirmation.error', Module::t('registration', 'The confirmation link is invalid or expired. Please try to request a new one.'));
        }

        return $this->render('confirm', [
            'module' => $this->module
        ]);
    }

    /**
     * Displays page where user can request new confirmation token.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend()
    {
        if (!$this->module->registrationConfirmation) {
            throw new NotFoundHttpException;
        }

        /** @var ResendForm $model */
        $model = Yii::createObject(ResendForm::className());

        if ($model->load(Yii::$app->request->post()) && $model->resend()) {
            Yii::$app->session->setFlash('resend.success', Module::t('registration', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));
        }

        return $this->render('resend', [
            'model' => $model
        ]);
    }

    public function actionEmail()
    {
        $user = User::findByUsername('admin');
        $user->afterRegister();
    }
}
