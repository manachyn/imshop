<?php

namespace im\users\controllers;

use amnah\yii2\user\models\Auth;
use im\users\models\LoginForm;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => \Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
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
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        return $this->goHome();
    }

    /**
     * Tries to authenticate user via social network. If user has already used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *
     * @param  ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
    {
        /** @var Auth $authClass */
        $authClass = $this->module->authModel;
        $auth = $authClass::findByClient($client);
        if (!$auth) {
            $auth = \Yii::createObject([
                'class' => $authClass::className(),
                'provider' => $client->getId(),
//                'provider' => $client->getName(),
                'provider_id' => $client->getUserAttributes()['id'],
                'provider_attributes' => json_encode($client->getUserAttributes())
            ]);
        }
    }

    /**
     * Tries to connect social account to user.
     *
     * @param ClientInterface $client
     */
    public function connect(ClientInterface $client)
    {
        forward_static_call([
            $this->module->modelMap['Account'],
            'connectWithUser',
        ], $client);
        $this->action->successUrl = Url::to(['/user/settings/networks']);
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
