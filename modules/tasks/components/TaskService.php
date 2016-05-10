<?php

namespace app\modules\tasks\components;

use app\modules\tasks\models\Task;
use yii\base\Component;

abstract class TaskService extends Component implements TaskHandlerInterface
{
//    /**
//     * Create task instance.
//     *
//     * @param array $data
//     * @param \Closure|null $callback
//     * @return Task
//     */
//    abstract public function createTask($data, \Closure $callback = null);

    /**
     * Handle task.
     *
     * @param TaskInterface $task
     * @return mixed
     */
    abstract public function handleTask(TaskInterface $task);
} 