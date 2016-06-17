<?php

namespace im\rbac\commands;

use im\rbac\components\AuthDataProviderInterface;
use im\rbac\Module;
use yii\console\Controller;
use Yii;
use yii\rbac\BaseManager;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\rbac\ManagerInterface;
use yii\rbac\Rule;


/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';

    /**
     * Initial RBAC action.
     */
    public function actionInit()
    {
        /** @var BaseManager $auth */
        $auth = Yii::$app->authManager;
        /** @var Module $module */
        $module = $this->module;
        foreach ($module->authDataProviders as $provider) {
            $provider = Yii::createObject($provider);
            if ($provider instanceof AuthDataProviderInterface) {
                foreach ($provider->getAuthRules() as $rule) {
                    if (!$rule instanceof Rule) {
                        $rule = Yii::createObject($rule);
                    }
                    if (!$auth->getRule($rule->name)) {
                        $auth->add($rule);
                    }
                }
                $items = $provider->getAuthItems();
                foreach ($provider->getAuthItems() as $name => $item) {
                    $this->addAuthItem($auth, $name, $item, $items);
                }
            }
        }
        if ($adminRole = $auth->getRole('admin')) {
            $auth->assign($adminRole, 1);
        }
    }

    /**
     * Add auth item and it's children recursively.
     * @param ManagerInterface $auth
     * @param string $name
     * @param array|Item $item
     * @param array $allItems
     * @return Item
     */
    protected function addAuthItem(ManagerInterface $auth, $name, $item, $allItems = [])
    {
        $authItem = $item;
        $children = null;
        if (!$authItem instanceof Item) {
            if ($item[0] == Item::TYPE_PERMISSION) {
                $authItem = $auth->createPermission($name);
                $authItem->description = $item[1];
                $children = isset($item[2]) ? $item[2] : [];
            } elseif ($item[0] == Item::TYPE_ROLE) {
                $authItem = $auth->createRole($name);
                $children = isset($item[1]) ? $item[1] : [];
            }
        }

        if (($authItem->type == Item::TYPE_PERMISSION && !$auth->getPermission($authItem->name))
            || ($authItem->type == Item::TYPE_ROLE && !$auth->getRole($authItem->name))) {
            $auth->add($authItem);
        }

        if (isset($children)) {
            foreach ($children as $childName) {
                if (($childAuthItem = (($permission = $auth->getPermission($childName)) ? $permission : $auth->getRole($childName)))
                    || isset($allItems[$childName]) && ($childAuthItem = $this->addAuthItem($auth, $childName, $allItems[$childName], $allItems))) {
                    if (!$auth->hasChild($authItem, $childAuthItem)) {
                        $auth->addChild($authItem, $childAuthItem);
                    }
                }
            }
        }

        return $authItem;
    }
}

