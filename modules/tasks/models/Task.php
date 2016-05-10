<?php

namespace app\modules\tasks\models;

use app\modules\queue\components\interfaces\QueueableEntityInterface;
use app\modules\queue\components\job\JobInterface;
use app\modules\tasks\components\TaskHandlerInterface;
use app\modules\tasks\components\TaskInterface;
use app\modules\tasks\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tasks}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Task extends ActiveRecord implements TaskInterface, QueueableEntityInterface
{
    const TYPE = 'task';

    /**
     * The underlying queue job instance.
     *
     * @var JobInterface
     */
    protected $job;

    /**
     * @inheritdoc
     */
    public static function instantiate($row)
    {
        return Yii::$container->has($row['type']) ? Yii::$container->get($row['type']) : new static;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tasks}}';
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
            [['status', 'updated_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('task', 'ID'),
            'type' => Module::t('task', 'Type'),
            'status' => Module::t('task', 'Status'),
            'statusString' => Module::t('task', 'Status'),
            'created_at' => Module::t('task', 'Created At'),
            'updated_at' => Module::t('task', 'Updated At'),
        ];
    }

    /**
     * @return array statuses list
     */
    public static function getStatusesList()
    {
        return [
            TaskInterface::STATUS_NEW => Module::t('task', 'New'),
            TaskInterface::STATUS_QUEUED => Module::t('task', 'In queue'),
            TaskInterface::STATUS_IN_PROGRESS => Module::t('task', 'In progress'),
            TaskInterface::STATUS_COMPLETED => Module::t('task', 'Completed'),
            TaskInterface::STATUS_FAILED => Module::t('task', 'Failed')
        ];
    }

    /**
     * @return string readable status
     */
    public function getStatusString()
    {
        $statuses = static::getStatusesList();

        return $statuses[$this->status];
    }

    /**
     * @inheritdoc
     */
    public function getQueueableId()
    {
        return $this->id;
    }

    /**
     * Set the queue job instance.
     *
     * @param JobInterface $job
     */
    public function setJob(JobInterface $job)
    {
        $this->job = $job;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new TaskQuery(get_called_class(), ['type' => static::TYPE !== Task::TYPE ? static::TYPE : null]);
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->type = self::TYPE;

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getHandler()
    {
        return null;
    }
}
