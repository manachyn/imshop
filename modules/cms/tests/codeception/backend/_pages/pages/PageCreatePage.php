<?php

namespace im\cms\tests\codeception\backend\_pages\pages;

use yii\codeception\BasePage;

/**
 * Represents page create page
 * @property \im\cms\tests\codeception\backend\AcceptanceTester|\im\cms\tests\codeception\backend\FunctionalTester $actor
 */
class PageCreatePage extends BasePage
{
    public $route = 'cms/page/create';

    protected $fields = [
        'status' => ['select'],
        'content' => ['textarea'],
        'meta_title' => ['input'],
        'meta_description' => ['input'],
        'custom_meta' => ['textarea'],
        'metaRobotsDirectives' => ['checkbox', true],
    ];

    /**
     * @param array $pageData
     */
    public function submit(array $pageData)
    {
        foreach ($pageData as $modelName => $modelData) {
            foreach ($modelData as $fieldName => $value) {

                $field = isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : ['input'];

                if ($field[0] === 'select') {
                    $this->actor->selectOption('select[name="' . $modelName . '[' . $fieldName . ']"]', $value);
                } elseif ($field[0] === 'checkbox') {
                    $this->actor->checkOption('input[name="' . $modelName . '[' . $fieldName . ']'
                        . (!empty($field[1]) ? '[]' : '') . '"][value="' . $value . '"]');
                } else {
                    $this->actor->fillField($field[0] . '[name="' . $modelName . '[' . $fieldName . ']"]', $value);
                }
            }
        }
        $this->actor->click('submit-button');
    }
}
