<?php

namespace im\cms\tests\codeception\backend\_pages\menus;

use im\cms\tests\codeception\common\_pages\BasePage;

/**
 * Represents menu create page
 */
class MenuCreatePage extends BasePage
{
    /**
     * @inheritdoc
     */
    public $route = 'cms/menu/create';

    /**
     * @inheritdoc
     */
    protected $fields = [
        'name' => ['input'],
        'location' => ['select']
    ];

    /**
     * @var array
     */
    protected $itemFields = [
        'label' => ['input'],
        'title' => ['input'],
        'url' => ['input'],
        'target_blank' => ['checkbox'],
        'css_classes' => ['input'],
        'rel' => ['input'],
        'status' => ['select'],
        'visibility' => ['input'],
        'items_display' => ['select'],
        'items_css_classes' => ['input'],
        'uploadedIcon' => ['file'],
        'uploadedActiveIcon' => ['file'],
        'uploadedVideo' => ['file']
    ];

    /**
     * @inheritdoc
     */
    protected $submitButton = 'submit-button';
}
