<?php

namespace im\users\backend\models;

/**
 * Class User
 * @package im\users\backend\models
 */
class User extends \im\users\models\User
{
    /**
     * @var string password
     */
    public $password;

    /**
     * @var string repeated password
     */
    public $password2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['password2', 'required', 'on' => [static::SCENARIO_CREATE]],
            ['password2', 'compare', 'compareAttribute' => 'password'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE][] = 'password2';
        $scenarios[self::SCENARIO_UPDATE][] = 'password2';

        return $scenarios;
    }
}

