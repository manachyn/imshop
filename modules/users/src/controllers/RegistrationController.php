<?php

namespace im\users\controllers;

use im\users\components\TokenException;
use im\users\models\ResendForm;
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
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        $userComponent = $this->getUserComponent();
        $model = $this->getRegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = $userComponent->register($model)) {
                Yii::$app->session->setFlash('registration.success', Module::t('registration', 'Your account has been created.'));
                if ($this->module->registrationConfirmation) {
                    Yii::$app->session->addFlash('registration.info', Module::t('registration', 'A message has been sent to your e-mail address. It contains a confirmation link that you have to click to complete registration.'));
                } elseif ($this->module->loginAfterRegistration) {
                    $userComponent->login($user);
                }
                if ($this->module->redirectAfterRegistration) {
                    return $this->redirect($this->module->redirectAfterRegistration);
                } else {
                    return $this->refresh();
                }
            } else {
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

        $userComponent = $this->getUserComponent();

        try {
            if ($user = $userComponent->confirm($token)) {
                Yii::$app->session->setFlash('confirmation.success', Module::t('registration', 'Thank you! Registration is complete now.'));
                if ($this->module->loginAfterRegistration) {
                    $userComponent->login($user);
                }
            } else {
                Yii::$app->session->setFlash('confirmation.error', Module::t('registration', 'Something went wrong and your account has not been confirmed.'));
            }
        } catch (TokenException $e) {
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

        $userComponent = $this->getUserComponent();
        $model = $this->getResendForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($userComponent->sendConfirmationToken($model->getUser())) {
                Yii::$app->session->setFlash('resend.success', Module::t('registration', 'A message has been sent to your e-mail address. It contains a confirmation link that you have to click to complete registration.'));
                return $this->refresh();
            }
        }

        return $this->render('resend', [
            'model' => $model
        ]);
    }

    /**
     * Returns user service.
     *
     * @return \im\users\components\UserComponent
     */
    protected function getUserComponent()
    {
        return Yii::$app->user;
    }

    /**
     * Returns registration form.
     *
     * @return \im\users\models\RegistrationForm
     */
    protected function getRegistrationForm()
    {
        return Yii::createObject($this->module->registrationForm);
    }

    /**
     * Returns registration form.
     *
     * @return \im\users\models\ResendForm
     */
    protected function getResendForm()
    {
        return Yii::createObject(ResendForm::className());
    }
}
