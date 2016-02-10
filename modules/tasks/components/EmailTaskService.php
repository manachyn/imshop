<?php

namespace app\modules\tasks\components;

use app\modules\tasks\models\SendEmailTask;

class EmailTaskService extends TaskService
{

//    /**
//     * Create task instance.
//     *
//     * @param array $data
//     * @param \Closure|null $callback
//     * @return Task
//     */
//    public function createTask($data, \Closure $callback = null)
//    {
//        // TODO: Implement createTask() method.
//    }

    /**
     * @inheritdoc
     */
    public function handleTask(TaskInterface $task)
    {
        /** @var SendEmailTask $task */
        $task->email;
    }
}