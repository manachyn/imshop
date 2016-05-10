<?php

namespace im\users\models;

use im\users\Module;
use im\users\traits\ModuleTrait;
use yii\db\ActiveRecord;
use Yii;

/**
 * Token model.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $type
 * @property integer $created_at
 * @property integer $expire_at
 *
 * @property User $user
 */
class Token extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @var int token for registration confirmation
     */
    const TYPE_REGISTRATION_CONFIRMATION = 1;

    /**
     * @var int token for password recovery
     */
    const TYPE_PASSWORD_RECOVERY = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tokens}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('token', 'ID'),
            'user_id' => Module::t('token', 'User ID'),
            'token' => Module::t('token', 'Token'),
            'type' => Module::t('token', 'Type'),
            'created_at' => Module::t('user', 'Created at')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->module->userModel, ['id' => 'user_id']);
    }

    /**
     * @return bool whether the token has expired.
     */
    public function isExpired()
    {
        return $this->expire_at < time();
    }

    /**
     * Generate token.
     *
     * @param int $userId
     * @param int $type
     * @param \DateTime $expireTime
     * @return static
     */
    public static function generate($userId, $type, \DateTime $expireTime = null)
    {
        $token = static::findByUserId($userId, $type);
        if (!$token) {
            $token = \Yii::createObject(static::className());
        }
        $token->user_id = $userId;
        $token->token = Yii::$app->security->generateRandomString();
        $token->type = $type;
        if ($expireTime) {
            $token->expire_at = $expireTime->getTimestamp();
        }
        $token->save(false);

        return $token;
    }

    /**
     * Find token by user ID.
     *
     * @param int $userId
     * @param int $type
     * @return static|null
     */
    public static function findByUserId($userId, $type)
    {
        return static::find()->where(['user_id' => $userId, 'type' => $type])
            ->andWhere(['or', ['>', 'expire_at', time()], ['expire_at' => null]])
            ->one();
    }

    /**
     * Find token by token string.
     *
     * @param string $token
     * @param int $type
     * @return static|null
     */
    public static function findByToken($token, $type)
    {
        return static::find()->where(['token' => $token, 'type' => $type])
            ->andWhere(['or', ['>', 'expire_at', time()], ['expire_at' => null]])
            ->one();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = time();
        }

        return parent::beforeSave($insert);
    }
}