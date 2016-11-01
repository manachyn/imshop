<?php

namespace im\cms\forms;

use im\forms\components\FormInterface;

/**
 * Class FeedbackForm
 * @package im\cms\forms
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FeedbackForm implements FormInterface
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        return '@im/cms/frontend/views/forms/_feedback';
    }
}