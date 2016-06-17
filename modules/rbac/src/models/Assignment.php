<?php

namespace im\rbac\models;

use im\rbac\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\BaseManager;
use yii\rbac\Item;

/**
 * Class Assignment
 * @package im\rbac\models
 */
class Assignment extends Model
{
    /**
     * @var array
     */
    public $roles = [];

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var BaseManager
     */
    protected $manager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->manager = Yii::$app->authManager;
        if ($this->user_id === null) {
            throw new InvalidConfigException('user_id must be set');
        }
        $this->roles = array_keys($this->manager->getRolesByUser($this->user_id));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Module::t('assignment', 'User ID'),
            'roles' => Module::t('assignment', 'Roles')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['roles', 'validateRoles'],
            ['user_id', 'integer']
        ];
    }

    /**
     * Validate roles.
     *
     * @param string $attribute
     */
    public function validateRoles($attribute)
    {
        if (is_array($this->$attribute)) {
            foreach ($this->$attribute as $role) {
                if (!$this->manager->getRole($role)) {
                    $this->addError('roles', Module::t('assignment', 'There is neither role with name "{0}"', [$role]));
                }
            }
        } else {
            $this->addError('roles', Module::t('assignment', 'Invalid roles value'));
        }
    }

    /**
     * Updates auth assignments for user.
     *
     * @return bool
     */
    public function updateAssignments()
    {
        if (!$this->validate()) {
            return false;
        }

        if (!is_array($this->roles)) {
            $this->roles = [];
        }

        $assignedItems = $this->manager->getRolesByUser($this->user_id);
        $assignedItemsNames = array_keys($assignedItems);

        foreach (array_diff($assignedItemsNames, $this->roles) as $item) {
            $this->manager->revoke($assignedItems[$item], $this->user_id);
        }

        foreach (array_diff($this->roles, $assignedItemsNames) as $item) {
            $this->manager->assign($this->manager->getRole($item), $this->user_id);
        }

        return true;
    }

    /**
     * Returns all available roles to be attached to user.
     *
     * @return array
     */
    public function getAvailableRoles()
    {
        return ArrayHelper::map($this->manager->getRoles(), 'name', function (\yii\rbac\Role $item) {
            return empty($item->description) ? $item->name : $item->name . ' (' . $item->description . ')';
        });
    }

    /**
     * Returns the roles and permissions that are assigned to the user.
     *
     * @param string|integer $userId
     * @return Item[]
     */
    public function getItemsByUser($userId)
    {
        return array_merge($this->manager->getRolesByUser($userId), $this->manager->getPermissionsByUser($userId));
    }
}
