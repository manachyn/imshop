<?php

namespace im\users\components;

use im\users\models\Profile;
use im\users\traits\ModuleTrait;
use im\users\models\RegistrationForm;
use im\users\models\User;
use Yii;
use yii\web\IdentityInterface;

/**
 * Extended class for the "user" application component.
 *
 * @package im\users\components
 */
class UserService extends \yii\web\User
{
    use ModuleTrait;

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
     * Register a user.
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
            $this->afterRegister($user);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function login(IdentityInterface $identity, $duration = 0)
    {
        if ($this->beforeLogin($identity, false, $duration)) {
            $this->switchIdentity($identity, $duration);
            $id = $identity->getId();
            $ip = Yii::$app->getRequest()->getUserIP();
            if ($this->enableSession) {
                $log = "User '$id' logged in from $ip with duration $duration.";
            } else {
                $log = "User '$id' logged in from $ip. Session not enabled.";
            }
            Yii::info($log, __METHOD__);
            $this->afterLogin($identity, false, $duration);
        }

        return !$this->getIsGuest();
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
}