<?php

namespace amnah\yii2\user\models;

use vova07\users\traits\ModuleTrait;
use Yii;
use yii\authclient\ClientInterface;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_user_auth".
 *
 * @property integer $id Id
 * @property integer $user_id User id
 * @property string $provider Name of service
 * @property string $provider_id Account id
 * @property string $provider_attributes Account properties returned by social network (json encoded)
 * @property integer $created_at Created time
 * @property integer $updated_at Updated time
 *
 * @property User $user
 */
class Auth extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'user_id' => Yii::t('user', 'User ID'),
            'provider' => Yii::t('user', 'Provider'),
            'provider_id' => Yii::t('user', 'Provider ID'),
            'provider_attributes' => Yii::t('user', 'Provider Attributes'),
            'create_time' => Yii::t('user', 'Created at'),
            'update_time' => Yii::t('user', 'Update Time'),
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
     * Set user id
     *
     * @param int $userId
     * @return static
     */
    public function setUser($userId)
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * Set provider attributes
     *
     * @param array $attributes
     * @return static
     */
    public function setProviderAttributes($attributes)
    {
        $this->provider_attributes = json_encode($attributes);
        return $this;
    }

    /**
     * Get provider attributes
     *
     * @return array
     */
    public function getProviderAttributes()
    {
        return json_decode($this->provider_attributes, true);
    }

    /**
     * Finds auth by client.
     *
     * @param ClientInterface $client
     * @return static|null
     */
    public static function findByClient(ClientInterface $client)
    {
        return static::find()->where([
            'provider'  => $client->getId(),
            'client_id' => $client->getUserAttributes()['id'],
        ])->one();
    }
}
