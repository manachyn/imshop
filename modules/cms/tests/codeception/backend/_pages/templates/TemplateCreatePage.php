<?php

namespace im\cms\tests\codeception\backend\_pages\templates;

use im\cms\tests\codeception\common\_pages\BasePage;

/**
 * Represents template create page
 */
class TemplateCreatePage extends BasePage
{
    /**
     * @inheritdoc
     */
    public $route = 'cms/template/create';

    /**
     * @inheritdoc
     */
    protected $fields = [
        'name' => ['input'],
        'layout_id' => ['select'],
        'default' => ['checkbox']
    ];

    /**
     * @inheritdoc
     */
    protected $submitButton = 'submit-button';

    /**
     * @inheritdoc
     */
    protected $form = '#template-form';
}
