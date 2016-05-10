<?php

namespace vova07\rbac\models;

use vova07\rbac\Module;
use Yii;
use yii\base\Model;

/**
 * Role model.
 *
 * @property string $name Role name
 * @property string $ruleName Rule name
 * @property string $description Role description
 * @property string $data Role data
 * @property string $rolesChildren Roles children
 * @property string $permissionsChildren Permissions children
 *
 * @property array $roles Roles array
 * @property array $rules Rules array
 * @property array $permissions Permissions array
 */
class Role extends Model
{
    /**
     * @var string Role name
     */
    public $name;

    /**
     * @var string Rule name
     */
    public $ruleName;

    /**
     * @var string Role description
     */
    public $description;

    /**
     * @var string Role data
     */
    public $data;

    /**
     * @var array|null Children roles
     */
    public $rolesChildren;

    /**
     * @var array|null Children permissions
     */
    public $permissionsChildren;

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
        $role = Yii::$app->authManager->getRole($name);

        if ($role !== null) {
            $model = new static(['name' => $name]);
            $model->loadValues($role);

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

            if ($this->name !== null) {
                unset($this->_roles[$this->name]);
            }
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
     * Validate roles children.
     *
     * @param string $attribute Attribute name
     * @param array $params Params
     */
    public function validateRolesChildren($attribute, $params)
    {
        $hasError = false;

        if (is_array($this->{$attribute})) {
            foreach ($this->{$attribute} as $child) {
                if (!array_key_exists($child, $this->roles)) {
                    $hasError = $hasError || true;
                }
            }
        } else {
            $hasError = true;
        }

        if ($hasError === true) {
            $this->addError($attribute, Module::t('rbac', 'ERROR_MSG_INVALID_ROLES_CHILDREN'));
        }
    }

    /**
     * Validate permissions children.
     *
     * @param string $attribute Attribute name
     * @param array $params Params
     */
    public function validatePermissionsChildren($attribute, $params)
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
            $this->addError($attribute, Module::t('rbac', 'ERROR_MSG_INVALID_PERMISSIONS_CHILDREN'));
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
            ['rolesChildren', 'validateRolesChildren'],
            ['permissionsChildren', 'validatePermissionsChildren']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'admin-create' => ['name', 'ruleName', 'description', 'data', 'rolesChildren', 'permissionsChildren'],
            'admin-update' => ['ruleName', 'description', 'data', 'rolesChildren', 'permissionsChildren']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('rbac', 'BACKEND_ROLES_ATTR_NAME'),
            'ruleName' => Module::t('rbac', 'BACKEND_ROLES_ATTR_RULE_NAME'),
            'description' => Module::t('rbac', 'BACKEND_ROLES_ATTR_DESCRIPTION'),
            'data' => Module::t('rbac', 'BACKEND_ROLES_ATTR_DATA'),
            'rolesChildren' => Module::t('rbac', 'BACKEND_ROLES_ATTR_ROLES'),
            'permissionsChildren' => Module::t('rbac', 'BACKEND_ROLES_ATTR_PERMISSIONS')
        ];
    }

    /**
     * Load model values.
     *
     * @param \yii\rbac\Role $role Role
     */
    protected function loadValues($role)
    {
        $children = array_keys(Yii::$app->authManager->getChildren($this->name));
        $roles = array_keys($this->roles);
        $permissions = array_keys($this->permissions);
        $this->description = $role->description;
        $this->ruleName = $role->ruleName;
        $this->data = $role->data;
        $this->rolesChildren = array_intersect($children, $roles);
        $this->permissionsChildren = array_intersect($children, $permissions);
    }

    /**
     * Add new role.
     *
     * @return boolean Whether role was added or not
     */
    public function add()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->createRole($this->name);
        $role->description = $this->description;
        $role->ruleName = $this->ruleName;
        $role->data = $this->data;

        if ($auth->add($role)) {
            if ($this->rolesChildren) {
                foreach ($this->rolesChildren as $child) {
                    $auth->addChild($role, $this->roles[$child]);
                }
            }
            if ($this->permissionsChildren) {
                foreach ($this->permissionsChildren as $child) {
                    $auth->addChild($role, $this->permissions[$child]);
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
        $role = $auth->createRole($this->name);
        $role->description = $this->description;
        $role->ruleName = $this->ruleName;
        $role->data = $this->data;

        if ($auth->update($this->name, $role)) {
            $auth->removeChildren($role);

            if ($this->rolesChildren) {
                foreach ($this->rolesChildren as $child) {
                    $auth->addChild($role, $this->roles[$child]);
                }
            }
            if ($this->permissionsChildren) {
                foreach ($this->permissionsChildren as $child) {
                    $auth->addChild($role, $this->permissions[$child]);
                }
            }
            return true;
        } else {
            return false;
        }
    }
}
