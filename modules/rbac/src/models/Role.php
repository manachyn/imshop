<?php

namespace im\rbac\models;

use im\rbac\Module;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * Class Role
 * @package im\rbac\models
 */
class Role extends AuthItem
{
    /**
     * @var array
     */
    public $childrenRoles = [];

    /**
     * @var array
     */
    public $childrenPermissions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->item instanceof Item) {
            $children = $this->manager->getChildren($this->name);
            $this->childrenRoles = array_keys(array_filter($children, function (Item $item) {
                return $item->type == Item::TYPE_ROLE;
            }));
            $this->childrenPermissions = array_keys(array_filter($children, function (Item $item) {
                return $item->type == Item::TYPE_PERMISSION;
            }));
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['childrenRoles', 'validateChildrenRoles'],
            ['childrenPermissions', 'validateChildrenPermissions'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = array_merge($scenarios['create'], ['childrenRoles', 'childrenPermissions']);
        $scenarios['update'] = array_merge($scenarios['update'], ['childrenRoles', 'childrenPermissions']);

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'childrenRoles' => Module::t('role', 'Children roles'),
            'childrenPermissions' => Module::t('role', 'Permissions')
        ]);
    }

    /**
     * Validate children roles.
     *
     * @param string $attribute
     * @param array $params
     */
    public function validateChildrenRoles($attribute, $params)
    {
        if (is_array($this->$attribute)) {
            foreach ($this->$attribute as $child) {
                if (!$this->manager->getRole($child)) {
                    $this->addError('childrenRoles', Module::t('role', 'There is neither role with name "{0}"', [$child]));
                }
            }
        } else {
            $this->addError('childrenRoles', Module::t('role', 'Invalid roles value'));
        }
    }

    /**
     * Validate children roles.
     *
     * @param string $attribute
     * @param array $params
     */
    public function validateChildrenPermissions($attribute, $params)
    {
        if (is_array($this->$attribute)) {
            foreach ($this->$attribute as $child) {
                if (!$this->manager->getPermission($child)) {
                    $this->addError('childrenPermissions', Module::t('role', 'There is neither permission with name "{0}"', [$child]));
                }
            }
        } else {
            $this->addError('childrenPermissions', Module::t('role', 'Invalid permissions value'));
        }
    }

    /**
     * @inheritdoc
     */
    public function getAvailableChildren()
    {
        return array_merge($this->getAvailableRoles(), $this->getAvailablePermissions());
    }

    /**
     * @return array
     */
    public function getAvailableRoles()
    {
        return ArrayHelper::map($this->manager->getRoles(), 'name', function (\yii\rbac\Role $item) {
            return empty($item->description) ? $item->name : $item->name . ' (' . $item->description . ')';
        });
    }

    /**
     * @return array
     */
    public function getAvailablePermissions()
    {
        return ArrayHelper::map($this->manager->getPermissions(), 'name', function (\yii\rbac\Permission $item) {
            return empty($item->description) ? $item->name : $item->name . ' (' . $item->description . ')';
        });
    }

    /**
     * @inheritdoc
     */
    protected function createItem($name)
    {
        return $this->manager->createRole($name);
    }

    /**
     * @inheritdoc
     */
    protected function updateChildren()
    {
        $this->children = array_merge($this->childrenRoles, $this->childrenPermissions);

        parent::updateChildren();
    }
}
