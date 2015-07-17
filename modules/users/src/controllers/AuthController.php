<?php

namespace im\users\controllers;

use im\users\clients\ClientInterface;
use Yii;
use yii\web\Controller;

/**
 * Class AuthController manages user authentication process via social clients.
 *
 * @package im\users\controllers
 */
class AuthController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
//            'connect' => [
//                'class' => 'yii\authclient\AuthAction',
//                'successCallback' => [$this, 'connectCallback'],
//                'successUrl' => Yii::$app->homeUrl . 'user/account',
//            ],
//            'login' => [
//                'class' => 'yii\authclient\AuthAction',
//                'successCallback' => [$this, 'loginRegisterCallback'],
//                'successUrl' => Yii::$app->homeUrl,
//            ],
        ];
    }

    public function onAuthSuccess(ClientInterface $client)
    {
        $userComponent = $this->getUserComponent();
        if (Yii::$app->user->isGuest) {
            // Login
            if ($userComponent->loginAuthClient($client)) {
                return;
            }
            if ($user = $userComponent->registerAuthClient($client)) {
                $userComponent->login($user);
            }
        } else {
            if ($userComponent->connectAuthClient($client, $userComponent->identity)) {
                //$this->action->successUrl = Url::to(['/user/settings/networks']);
            }
        }
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
}