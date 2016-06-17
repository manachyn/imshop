<?php

namespace im\testsBoilerplate\codeception\common\_pages;

/**
 * Class BasePage
 * @package im\testsBoilerplate\codeception\common\_pages
 * @property \im\testsBoilerplate\codeception\backend\AcceptanceTester|\im\testsBoilerplate\codeception\backend\FunctionalTester $actor
 */
abstract class BasePage extends \yii\codeception\BasePage
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var string
     */
    protected $submitButton = 'button[type=submit]';

    /**
     * @var string
     */
    protected $form = 'form';

    /**
     * @param array $data
     */
    public function submit(array $data)
    {
        $this->fillFields($data);
        $this->actor->click($this->submitButton);
    }

    public function fillFields(array $data, $fields = null)
    {
        if ($fields === null) {
            $fields = $this->fields;
        }
        foreach ($data as $modelName => $modelData) {
            foreach ($modelData as $fieldName => $value) {

                $field = isset($fields[$fieldName]) ? $fields[$fieldName] : ['input'];

                if ($field[0] === 'select') {
                    $this->actor->selectOption('select[name="' . $modelName . '[' . $fieldName . ']"]', $value);
                } elseif ($field[0] === 'checkbox') {
                    $this->actor->checkOption('input[name="' . $modelName . '[' . $fieldName . ']'
                        . (!empty($field[1]) ? '[]' : '') . '"][value="' . $value . '"]');
                } elseif ($field[0] === 'file') {
                    $this->actor->attachFile('input[name="' . $modelName . '[' . $fieldName . ']'
                        . (!empty($field[1]) ? '[]' : '') . '"][type="file"]', $value);
                } else {
                    $this->actor->fillField($field[0] . '[name="' . $modelName . '[' . $fieldName . ']"]', $value);
                }
            }
        }
    }

    /**
     * @param array $data
     */
    public function submitForm(array $data)
    {
        $this->actor->submitForm($this->form, $data);
    }
}