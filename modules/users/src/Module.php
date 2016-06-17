<?php

namespace im\users;

use im\base\traits\ModuleTranslateTrait;
use im\users\components\UserEventsHandler;
use im\users\components\UserMailerInterface;
use Yii;
use yii\base\Component;
use yii\di\Instance;

/**
 * Class Module
 * @package im\users
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'users';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\users\controllers';

    /**
     * @var bool whether to enable registration.
     */
    public $enableRegistration = true;

    /**
     * @var bool whether the user have to confirm registration
     */
    public $registrationConfirmation = true;

    /**
     * @var bool whether to login user after registration
     */
    public $loginAfterRegistration = true;

    /**
     * @var string|array the url to which to redirect the user after registration
     */
    public $redirectAfterRegistration = 'success';

    /**
     * @var bool whether to enable automatic password generation.
     */
    public $passwordAutoGenerating = false;

    /**
     * @var int generated password length.
     */
    public $passwordLength = 5;

    /**
     * @var string time before password recovery token expire.
     */
    public $passwordRecoveryTokenExpiration = '48 hours';

    /**
     * @var UserEventsHandler|array|string the handler object or the application component ID of the handler
     */
    public $userEventsHandler = [
        'class' => 'im\users\components\UserEventsHandler',
        'mailer' => [
            'class' => 'im\users\components\UserMailer'
        ]
    ];

    /**
     * @var string user model class
     */
    public $userModel = 'im\users\models\User';

    /**
     * @var string backend user model class
     */
    public $backendUserModel = 'im\users\backend\models\User';

    /**
     * @var string profile model class
     */
    public $profileModel = 'im\users\models\Profile';

    /**
     * @var string token model class
     */
    public $tokenModel = 'im\users\models\Token';

    /**
     * @var string auth model class
     */
    public $authModel = 'im\users\models\Auth';

    /**
     * @var string registration form model class
     */
    public $registrationForm = 'im\users\models\RegistrationForm';

    /**
     * @var string profile form model class
     */
    public $profileForm = 'im\users\models\ProfileForm';

    /**
     * @var string The prefix for user module URL.
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>'                    => 'profile/show',
        '<action:(login|logout)>'     => 'security/<action>',
        '<action:(register|resend)>'  => 'registration/<action>',
        'confirm/<id:\d+>/<code:\w+>' => 'registration/confirm',
        'forgot'                      => 'recovery/request',
        'recover/<id:\d+>/<code:\w+>' => 'recovery/reset',
        'settings/<action:\w+>'       => 'settings/<action>'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

//        if ($this->userEventsHandler !== null && !is_object($this->userEventsHandler)) {
//            $this->userEventsHandler = Yii::createObject($this->userEventsHandler);
//        }
    }
}