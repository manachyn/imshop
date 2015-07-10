<?php

namespace im\users\models;

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
class Profile extends ActiveRecord
{
    /**
     * @var string the name of the register scenario.
     */
    const SCENARIO_REGISTER = 'register';

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
            static::SCENARIO_REGISTER => ['first_name', 'last_name', 'user_id']
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
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
