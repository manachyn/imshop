<?php

namespace app\modules\queue\components\job;

use app\modules\queue\components\QueueableTrait;

/**
 * Base class for queueable jobs
 */
abstract class BaseJob
{
    use QueueableTrait;
} 