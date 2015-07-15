<?php

namespace im\users\components;

use im\users\models\Profile;
use im\users\models\Token;
use im\users\traits\ModuleTrait;
use im\users\models\RegistrationForm;
use im\users\models\User;
use Yii;
use yii\di\Instance;
use yii\web\IdentityInterface;

/**
 * Extended class for the "user" application component.
 *
 * @package im\users\components
 */
class UserComponent extends \yii\web\User
{
    use ModuleTrait;

    /**
     * @var UserMailerInterface|array|string the mailer object or the application component ID of the mailer object.
     */
    public $mailer = 'im\users\components\UserMailer';

    /**
     * @event ModelEvent an event that is triggered before user registration.
     */
    const EVENT_BEFORE_REGISTRATION = 'beforeRegistration';

    /**
     * @event Event an event that is triggered after a user is registered.
     */
    const EVENT_AFTER_REGISTRATION = 'afterRegistration';

    /**
     * @event ModelEvent an event that is triggered before user confirmation.
     */
    const EVENT_BEFORE_CONFIRMATION = 'beforeConfirmation';

    /**
     * @event Event an event that is triggered after a user is confirmed.
     */
    const EVENT_AFTER_CONFIRMATION = 'afterConfirmation';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'im\users\components\UserMailerInterface');
    }

    /**
     * Registers a user.
     *
     * @param \im\users\models\RegistrationForm $form
     * @return User|null the saved user or null if saving fails
     */
    public function register(RegistrationForm $form)
    {
        /** @var User $userClass */
        $userClass = $this->module->userModel;
        /** @var User $user */
        $user = Yii::$container->get($userClass, [], ['scenario' => $userClass::SCENARIO_REGISTER]);
        /** @var Profile $profileClass */
        $profileClass = $this->module->profileModel;
        /** @var Profile $profile */
        $profile = Yii::$container->get($profileClass, [], ['scenario' => $profileClass::SCENARIO_REGISTER]);

        $user->setAttributes([
            'username' => $form->username,
            'email' => $form->email,
            'password' => $form->password,
            'registration_ip' => ip2long(Yii::$app->request->userIP)
        ]);

        $profile->setAttributes([
            'first_name' => $form->firstName,
            'last_name' => $form->lastName
        ]);

        $user->profile = $profile;

        if (!$this->beforeRegister($user)) {
            return null;
        }
        if ($user->save() && $user->profile->save()) {
            $user->link('profile', $user->profile);
            if ($this->module->registrationConfirmation) {
                $this->sendConfirmationToken($user);
            }
            $this->afterRegister($user);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Confirms a user.
     *
     * @param string $token
     * @throws TokenException
     * @return User|null the confirmed user or null if confirmation fails
     */
    public function confirm($token)
    {
        /** @var Token $tokenClass */
        $tokenClass = $this->module->tokenModel;
        /** @var Token $token */
        $token = $tokenClass::findByToken($token, $tokenClass::TYPE_REGISTRATION_CONFIRMATION);

        if (!$token) {
            throw new TokenException('Token is not found.');
        }

        /** @var User $userClass */
        $userClass = $this->module->userModel;
        /** @var User $user */
        $user = $userClass::findOne($token->user_id);

        if (!$this->beforeConfirm($user)) {
            return null;
        }
        $user->status = $userClass::STATUS_ACTIVE;
        $user->confirmed_at = time();
        if ($user->save()) {
            $token->delete();
            $this->afterConfirm($user);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @param User $user user object
     * @return bool whether the confirmation information was resent.
     */
    public function sendConfirmationToken(User $user)
    {
        /** @var Token $tokenClass */
        $tokenClass = $this->module->tokenModel;
        $token = $tokenClass::generate($user->getId(), $tokenClass::TYPE_REGISTRATION_CONFIRMATION);
        $this->mailer->sendRegistrationConfirmationEmail($user, $token);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if (parent::login($identity, $duration)) {
            /** @var User $identity */
            $identity->last_login_ip = ip2long(Yii::$app->request->userIP);
            $identity->last_login_at = time();
            return true;
        } else {
            return false;
        }
    }

    /**
     * This method is called at the beginning of user registration.
     * The default implementation will trigger an [[EVENT_BEFORE_REGISTRATION]] event.
     *
     * @param User $user user object
     * @return bool whether the registration should continue.
     */
    protected function beforeRegister(User $user)
    {
        $event = new UserEvent(['user' => $user]);
        $this->trigger(self::EVENT_BEFORE_REGISTRATION, $event);

        return $event->isValid;
    }

    /**
     * This method is called at the end of user registration.
     * The default implementation will trigger an [[EVENT_AFTER_REGISTRATION]] event.
     *
     * @param User $user
     */
    protected function afterRegister(User $user)
    {
        $event = new UserEvent(['user' => $user]);
        $this->trigger(self::EVENT_AFTER_REGISTRATION, $event);
    }

    /**
     * This method is called at the beginning of user confirmation.
     * The default implementation will trigger an [[EVENT_BEFORE_CONFIRMATION]] event.
     *
     * @param User $user user object
     * @return boolean whether the confirmation should continue.
     */
    public function beforeConfirm(User $user)
    {
        $event = new UserEvent(['user' => $user]);
        $this->trigger(self::EVENT_BEFORE_CONFIRMATION, $event);

        return $event->isValid;
    }

    /**
     * This method is called at the end of user confirmation.
     * The default implementation will trigger an [[EVENT_AFTER_CONFIRMATION]] event.
     *
     * @param User $user
     */
    public function afterConfirm(User $user)
    {
        $event = new UserEvent(['user' => $user]);
        $this->trigger(self::EVENT_AFTER_CONFIRMATION, $event);
    }
}