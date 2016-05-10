<?php

namespace im\users\controllers;

use im\users\models\PasswordResetForm;
use im\users\models\RecoveryForm;
use im\users\Module;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class RecoveryController manages password recovery process.
 *
 * @package im\users\controllers
 */
class RecoveryController extends Controller
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
                    ['allow' => true, 'actions' => ['index', 'reset'], 'roles' => ['?']],
                ]
            ],
        ];
    }

    /**
     * Shows page where user can request password recovery.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $userComponent = $this->getUserComponent();
        $model = $this->getRecoveryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($userComponent->sendRecoveryToken($model->getUser())) {
                Yii::$app->session->setFlash('recovery.success', Module::t('recovery', 'An email has been sent with instructions for resetting your password.'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('recovery.error', Module::t('recovery', 'Password recovery failed. Please try again!'));
            }
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * Confirms user's account.
     *
     * @param string $token
     * @return mixed
     */
    public function actionReset($token)
    {
        $userComponent = $this->getUserComponent();
        $model = $this->getPasswordResetForm();
        $token = $userComponent->getPasswordRecoveryToken($token);
        if ($token && $token->user) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($userComponent->resetPassword($token->user, $model->password)) {
                    $token->delete();
                    Yii::$app->session->setFlash('reset.success', Module::t('recovery', 'The password has been changed.'));
                } else {
                    Yii::$app->session->setFlash('reset.error', Module::t('recovery', 'The password has not been changed. Please try again!'));
                }
            }
        } else {
            Yii::$app->session->setFlash('reset.token.error', Module::t('recovery', 'The recovery link is invalid or expired. Please try to request a new one.'));
        }

        return $this->render('reset', [
            'model' => $model
        ]);
    }

    /**
     * Returns user component.
     *
     * @return \im\users\components\UserComponent
     */
    protected function getUserComponent()
    {
        return Yii::$app->user;
    }

    /**
     * Returns recovery form.
     *
     * @return \im\users\models\RecoveryForm
     */
    protected function getRecoveryForm()
    {
        return Yii::createObject(RecoveryForm::className());
    }

    /**
     * Returns password reset form.
     *
     * @return \im\users\models\PasswordResetForm
     */
    protected function getPasswordResetForm()
    {
        return Yii::createObject(PasswordResetForm::className());
    }
}