<?php

namespace vova07\rbac\models;

use vova07\rbac\Module;
use Yii;
use yii\base\Model;

/**
 * Rule model.
 *
 * @property string $namespace Rule namespace
 */
class Rule extends Model
{
    /**
     * @var string Rule class namespace
     */
    public $namespace;

    /**
     * Validate namespace.
     *
     * @param string $attribute Attribute name
     * @param array $params Params
     */
    public function validateNamespace($attribute, $params)
    {
        $hasError = false;

        if (class_exists($this->{$attribute})) {
            $rule = new $this->{$attribute};

            if (!$rule instanceof \yii\rbac\Rule) {
                $hasError = true;
            }
        } else {
            $hasError = true;
        }

        if ($hasError === true) {
            $this->addError($attribute, Module::t('rbac', 'ERROR_MSG_INVALID_NAMESPACE'));
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['namespace', 'required'],
            ['namespace', 'validateNamespace'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('rbac', 'BACKEND_RULES_ATTR_NAME'),
            'namespace' => Module::t('rbac', 'BACKEND_RULES_ATTR_NAMESPACE')
        ];
    }

    /**
     * Add new rule.
     *
     * @return boolean Whether rule was added or not
     */
    public function add()
    {
        $auth = Yii::$app->authManager;
        $rule = new $this->namespace;

        return $auth->add($rule);
    }
}
