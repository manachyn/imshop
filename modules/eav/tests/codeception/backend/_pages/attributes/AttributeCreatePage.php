<?php

namespace im\eav\tests\codeception\backend\_pages\templates;

use im\eav\tests\codeception\common\_pages\BasePage;

/**
 * Represents attribute create page
 */
class AttributeCreatePage extends BasePage
{
    /**
     * @inheritdoc
     */
    public $route = 'eav/attribute/create';

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
