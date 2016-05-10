<?php

namespace im\users\models;

use im\users\traits\ModuleTrait;
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
            'id' => Yii::t('auth', 'ID'),
            'user_id' => Yii::t('auth', 'User ID'),
            'provider' => Yii::t('auth', 'Provider'),
            'provider_id' => Yii::t('auth', 'Provider ID'),
            'provider_attributes' => Yii::t('auth', 'Provider attributes'),
            'create_time' => Yii::t('auth', 'Created at'),
            'update_time' => Yii::t('auth', 'Updated at')
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
     * Finds auth by client.
     *
     * @param ClientInterface $client
     * @return static|null
     */
    public static function findByClient(ClientInterface $client)
    {
        return static::find()->where([
            'provider' => $client->getId(),
            'provider_id' => $client->getUserAttributes()['id']
        ])->one();
    }

    /**
     * Creates auth instance by from client.
     *
     * @param ClientInterface $client
     * @return static
     */
    public static function getInstance(ClientInterface $client)
    {
        return  Yii::createObject([
            'class' => static::className(),
            'provider' => $client->getId(),
            'provider_id' => $client->getUserAttributes()['id'],
            'provider_attributes' => json_encode($client->getUserAttributes())
        ]);
    }
}
