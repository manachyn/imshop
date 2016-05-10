<?php

namespace app\modules\tasks\models;

use app\modules\queue\components\interfaces\QueuedInterface;

class SendEmailTask extends Task implements QueuedInterface
{
    const TYPE = 'send_email';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Send email';
    }

    /**
     * @inheritdoc
     */
    public function getHandler()
    {
        return \Yii::$container->get('app\modules\tasks\components\EmailTaskService');
    }
}