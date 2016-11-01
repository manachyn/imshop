<?php

namespace im\forms\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class FormRequest
 *
 * @property int $id
 * @property string $request_type
 * @property int $form_id
 * @property string $form_name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package im\forms\models
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FormRequest extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$container->has($row['request_type']) ? Yii::$container->get($row['request_type']) : new static;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_requests}}';
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
            [['request_type', 'form_name'], 'string', 'max' => 100],
            [['form_id', 'created_at', 'updated_at'], 'integer']
        ];
    }
}
