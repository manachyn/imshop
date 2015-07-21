<?php

namespace im\filesystem\models;

use yii\db\ActiveRecord;

/**
 * Class EntityFile
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property string attribute
 * @property File $file
 *
 * @package im\filesystem\models
 */
class EntityFile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%entity_files}}';
    }
}
