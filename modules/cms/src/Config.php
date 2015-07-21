<?php

namespace im\cms;

use im\config\models\ComponentConfig;

class Config extends ComponentConfig
{
    public $isOpenGraphEnabled = true;

    /**
     * @inheritdoc
     */
    public function getEditView()
    {
        return '@app/modules/cms/backend/views/_config';
    }
}