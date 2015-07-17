<?php

namespace im\users\controllers;

use im\users\models\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class SecurityController manages user authentication process.
 *
 * @property \im\users\Module $module
 * @package im\users\controllers
 */
class SecurityController extends Controller
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
                    ['allow' => true, 'actions' => ['login'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'logout'], 'roles' => ['@']]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ]
            ]
        ];
    }

    /**
     * Displays the login page.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $userComponent = $this->getUserComponent();
        $model = $this->getLoginForm();

        if ($model->load(Yii::$app->request->post()) && $userComponent->login($model->getUser())) {
            return $this->goBack();
        }

        return $this->render('login', [
            'module' => $this->module,
            'model' => $model
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $this->getUserComponent()->logout();

        return $this->goHome();
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
     * Returns login form.
     *
     * @return \im\users\models\LoginForm
     */
    protected function getLoginForm()
    {
        return Yii::createObject(LoginForm::className());
    }
}
