<?php

namespace im\rbac\models;

use im\rbac\Module;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rbac\BaseManager;
use yii\rbac\Item;

/**
 * Authorization item
 * @package im\rbac\models
 */
abstract class AuthItem extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $ruleName;

    /**
     * @var string
     */
    public $data;

    /**
     * @var array
     */
    public $children = [];

    /**
     * @var Item
     */
    public $item;

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
        if ($this->item instanceof Item) {
            $this->name = $this->item->name;
            $this->description = $this->item->description;
            $this->ruleName = $this->item->ruleName;
            $this->data = $this->item->data === null ? null : Json::encode($this->item->data);
            $this->children = array_keys($this->manager->getChildren($this->item->name));
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'match', 'pattern' => '/^[\w][\w-.:]+[\w]$/'],
            ['name', 'validateName', 'when' => function () {
                return $this->scenario == 'create' || $this->item->name != $this->name;
            }],
            [['name', 'ruleName'], 'string', 'max' => 64],
            [['name', 'description', 'rule'], 'trim'],
            [['description', 'data'], 'string'],
            ['ruleName', 'in', 'range' => array_keys($this->manager->getRules()),
                'message' => Module::t('auth-item', 'Rule not exists')],
            ['children', 'validateChildren'],
        ];
    }

    /**
     * Validate auth item name.
     *
     * @param string $attribute
     */
    public function validateName($attribute)
    {
        if ($this->manager->getRole($this->$attribute) !== null || $this->manager->getPermission($this->$attribute) !== null) {
            $this->addError('name', Module::t('auth-item', 'Auth item with such name already exists'));
        }
    }

    /**
     * Validate auth item children.
     *
     * @param string $attribute
     */
    public function validateChildren($attribute)
    {
        if (is_array($this->$attribute)) {
            foreach ($this->$attribute as $child) {
                if ($this->manager->getRole($child) === null && $this->manager->getPermission($child) === null) {
                    $this->addError('children', Module::t('auth-item', 'There is neither role or permission with name "{0}"', [$child]));
                }
            }
        } else {
            $this->addError('children', Module::t('auth-item', 'Invalid children value'));
        }
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'create' => ['name', 'ruleName', 'description', 'data', 'children'],
            'update' => ['name', 'ruleName', 'description', 'data', 'children']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('auth-item', 'Name'),
            'ruleName' => Module::t('auth-item', 'Rule name'),
            'description' => Module::t('auth-item', 'Description'),
            'data' => Module::t('auth-item', 'Data'),
            'children' => Module::t('auth-item', 'Children')
        ];
    }

    /**
     * Saves item.
     *
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $item = $this->createItem($this->name);
        $item->name = $this->name;
        $item->description = $this->description;
        $item->ruleName = $this->ruleName;
        $item->data = $this->data === null || $this->data === '' ? null : Json::decode($this->data);

        if ($this->item === null) {
            $this->manager->add($item);
        } else {
            $this->manager->update($this->item->name, $item);
        }

        $this->item = $item;

        $this->updateChildren();

        return true;
    }

    /**
     * Updated item children.
     */
    protected function updateChildren()
    {
        $children = $this->manager->getChildren($this->item->name);
        $childrenNames = array_keys($children);

        if (is_array($this->children)) {
            foreach (array_diff($childrenNames, $this->children) as $item) {
                $this->manager->removeChild($this->item, $children[$item]);
            }
            foreach (array_diff($this->children, $childrenNames) as $item) {
                $item = ($permission = $this->manager->getPermission($item)) ? $permission : $this->manager->getRole($item);
                $this->manager->addChild($this->item, $item);
            }
        } else {
            $this->manager->removeChildren($this->item);
        }
    }

    /**
     * @return array
     */
    public function getAvailableRules()
    {
        return ArrayHelper::map($this->manager->getRules(), 'name', 'name');
    }

    /**
     * @return array
     */
    abstract public function getAvailableChildren();

    /**
     * @param string $name
     * @return \yii\rbac\Item
     */
    abstract protected function createItem($name);
}
