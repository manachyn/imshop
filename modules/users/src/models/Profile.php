<?php

namespace im\users\models;

use im\users\components\ProfileInterface;
use im\users\Module;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * User profile model.
 *
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar_url
 *
 * @property User $user
 */
class Profile extends ActiveRecord implements ProfileInterface
{
    /**
     * @var string the name of the register scenario.
     */
    const SCENARIO_REGISTER = 'register';

    /**
     * @var string the name of the create scenario.
     */
    const SCENARIO_CREATE = 'create';

    /**
     * @var string the name of the update scenario.
     */
    const SCENARIO_UPDATE = 'update';

    /**
     * @var string the name of the profile scenario.
     */
    const SCENARIO_PROFILE = 'profile';

    /**
     * @var string the name of the connect scenario.
     */
    const SCENARIO_CONNECT = 'connect';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'avatar_url'], 'string', 'max' => 100]
        ];
    }

    public function scenarios()
    {
        return [
            static::SCENARIO_DEFAULT => ['first_name', 'last_name', 'user_id'],
            static::SCENARIO_REGISTER => ['first_name', 'last_name', 'user_id'],
            static::SCENARIO_CREATE => ['first_name', 'last_name', 'user_id'],
            static::SCENARIO_UPDATE => ['first_name', 'last_name', 'user_id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => Module::t('user', 'First name'),
            'last_name' => Module::t('user', 'Last name'),
            'avatar_url' => Module::t('user', 'Avatar URL')
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('profile');
    }

    /**
     * Returns full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Sets full name.
     *
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        list($this->first_name, $this->last_name) = explode(' ', $fullName);
    }
}

