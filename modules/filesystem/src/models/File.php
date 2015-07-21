<?php

namespace im\filesystem\models;

use im\filesystem\Module;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class File
 *
 * @property integer $id
 * @property string $filesystem
 * @property string $path
 * @property integer $size
 * @property string $mime_type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package im\filesystem\exception
 */
class File extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['path', 'required'],
            [['filesystem', 'size', 'mime_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('file', 'ID'),
            'filesystem' => Module::t('file', 'Filesystem'),
            'path' => Module::t('file', 'Path'),
            'size' => Module::t('file', 'Size'),
            'mime_type' => Module::t('file', 'Mime type'),
            'created_at' => Module::t('user', 'Created at'),
            'updated_at' => Module::t('user', 'Updated at')
        ];
    }
}