<?php

namespace app\modules\rbac\models;

use app\modules\rbac\Module;
use Yii;
use yii\base\Model;

/**
 * Permission model.
 *
 * @property string $name Permission name
 * @property string $ruleName Rule name
 * @property string $description Permission description
 * @property string $data Permission data
 * @property array $children Permission children
 *
 * @property array $roles Roles array
 * @property array $rules Rules array
 * @property array $permissions Permissions array
 */
class Permission extends Model
{
    /**
     * @var string Permission name
     */
    public $name;

    /**
     * @var string Permission name
     */
    public $ruleName;

    /**
     * @var string Permission description
     */
    public $description;

    /**
     * @var string Permission data
     */
    public $data;

    /**
     * @var array|null Permission children
     */
    public $children;

    /**
     * @var \yii\rbac\Role[]|null Roles array
     */
    protected $_roles;

    /**
     * @var \yii\rbac\Permission[]|null Permissions array
     */
    protected $_permissions;

    /**
     * @var \yii\rbac\Rule[]|null Rules array
     */
    protected $_rules;

    /**
     * Find role by name.
     *
     * @param string $name Role name
     *
     * @return Role|null Populated Role model
     */
    public static function findIdentity($name)
    {
        $permission = Yii::$app->authManager->getPermission($name);

        if ($permission !== null) {
            $model = new static(['name' => $name]);
            $model->loadValues($permission);

            return $model;
        }
        return null;
    }

    /**
     * @return null|\yii\rbac\Role[] Roles array
     */
    public function getRoles()
    {
        if ($this->_roles === null) {
            $this->_roles = Yii::$app->authManager->getRoles();
        }
        return $this->_roles;
    }

    /**
     * @return null|\yii\rbac\Permission[] Permissions array
     */
    public function getPermissions()
    {
        if ($this->_permissions === null) {
            $this->_permissions = Yii::$app->authManager->getPermissions();

            if ($this->name !== null) {
                unset($this->_permissions[$this->name]);
            }
        }
        return $this->_permissions;
    }

    /**
     * @return null|\yii\rbac\Rule[] Rules array
     */
    public function getRules()
    {
        if ($this->_rules === null) {
            $this->_rules = Yii::$app->authManager->getRules();
        }
        return $this->_rules;
    }

    /**
     * Validate permissions children.
     *
     * @param string $attribute Attribute name
     * @param array $params Params
     */
    public function validateChildren($attribute, $params)
    {
        $hasError = false;

        if (is_array($this->{$attribute})) {
            foreach ($this->{$attribute} as $child) {
                if (!array_key_exists($child, $this->permissions)) {
                    $hasError = $hasError || true;
                }
            }
        } else {
            $hasError = true;
        }

        if ($hasError === true) {
            $this->addError($attribute, Module::t('rbac', 'ERROR_MSG_INVALID_CHILDREN'));
        }
    }

    /**
     * Validate rule name.
     *
     * @param string $attribute Attribute name
     * @param array $params Params
     */
    public function validateRuleName($attribute, $params)
    {
        $hasError = false;

        if (is_string($this->{$attribute})) {
            if (!array_key_exists($this->{$attribute}, $this->rules)) {
                $hasError = true;
            }
        } else {
            $hasError = true;
        }

        if ($hasError === true) {
            $this->addError($attribute, Module::t('rbac', 'ERROR_MSG_INVALID_RULE_NAME'));
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'string', 'max' => 64],
            ['name', 'match', 'pattern' => '/^[a-z0-9-_а-я]+/iu'],
            [['description', 'data'], 'string'],
            ['ruleName', 'validateRuleName'],
            ['children', 'validateChildren'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'admin-create' => ['name', 'ruleName', 'description', 'data', 'children'],
            'admin-update' => ['ruleName', 'description', 'data', 'children']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_NAME'),
            'ruleName' => Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_RULE_NAME'),
            'description' => Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_DESCRIPTION'),
            'data' => Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_DATA'),
            'children' => Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_CHILDREN'),
        ];
    }

    /**
     * Load model values.
     *
     * @param \yii\rbac\Permission $permission Permission
     */
    protected function loadValues($permission)
    {
        $children = array_keys(Yii::$app->authManager->getChildren($this->name));
        $this->description = $permission->description;
        $this->ruleName = $permission->ruleName;
        $this->data = $permission->data;
        $this->children = $children;
    }

    /**
     * Add new role.
     *
     * @return boolean Whether role was added or not
     */
    public function add()
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->description;
        $permission->ruleName = $this->ruleName;
        $permission->data = $this->data;

        if ($auth->add($permission)) {
            if ($this->children) {
                foreach ($this->children as $child) {
                    $auth->addChild($permission, $this->permissions[$child]);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $auth = Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->description;
        $permission->ruleName = $this->ruleName;
        $permission->data = $this->data;

        if ($auth->update($this->name, $permission)) {
            $auth->removeChildren($permission);

            if ($this->children) {
                foreach ($this->children as $child) {
                    $auth->addChild($permission, $this->permissions[$child]);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
