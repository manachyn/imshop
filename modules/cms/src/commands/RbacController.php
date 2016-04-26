<?php

namespace im\cms\commands;

use Yii;
use yii\console\Controller;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;

/**
 * CMS RBAC controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'add';

    /**
     * @var array Permission
     */
    public $permissions = [
        'administratePages' => [
            'description' => 'Can administrate all "Pages" module',
            'children' => [
                'BackendViewPages' => [
                    'description' => 'Can view backend Pages list'
                ],
                'BackendCreatePages' => [
                    'description' => 'Can create backend Pages'
                ],
                'BackendUpdatePages' => [
                    'description' => 'Can update backend Pages'
                ],
                'BackendDeletePages' => [
                    'description' => 'Can delete backend Pages'
                ]
            ]
        ],
        'administrateMenus' => [
            'description' => 'Can administrate all "Menus" module',
            'children' => [
                'BackendViewMenus' => [
                    'description' => 'Can view backend Menus list'
                ],
                'BackendCreateMenus' => [
                    'description' => 'Can create backend Menus'
                ],
                'BackendUpdateMenus' => [
                    'description' => 'Can update backend Menus'
                ],
                'BackendDeleteMenus' => [
                    'description' => 'Can delete backend Menus'
                ]
            ]
        ]
    ];

    /**
     * Add comments RBAC.
     */
    public function actionAdd()
    {
        $auth = Yii::$app->authManager;
        $superAdmin = $auth->getRole('superadmin');

        foreach ($this->permissions as $name => $option) {
            $permission  = $this->createPermission($auth, $name, $option);
            $auth->addChild($superAdmin, $permission);
        }

        return static::EXIT_CODE_NORMAL;
    }

    /**
     * Remove comments RBAC.
     */
    public function actionRemove()
    {
        $auth = Yii::$app->authManager;

        foreach ($this->permissions as $name => $option) {
            $this->removePermission($auth, $name, isset($option['children']) ? $option['children'] : array());
        }

        return static::EXIT_CODE_NORMAL;
    }

    /**
     * @param ManagerInterface $auth
     * @param string $name
     * @param array $options
     * @param Permission $parent
     * @return Permission
     */
    private function createPermission($auth, $name, $options = array(), $parent = null) {
        $permission = $auth->createPermission($name);
        if (isset($option['description'])) {
            $permission->description = $option['description'];
        }
        if (isset($option['rule'])) {
            $permission->ruleName = $option['rule'];
        }
        $auth->add($permission);
        if ($parent)
            $auth->addChild($parent, $permission);

        if (isset($options['children'])) {
            foreach ($options['children'] as $childName => $childOptions) {
                $this->createPermission($auth, $childName, $childOptions, $permission);
            }
        }

        return $permission;
    }

    /**
     * @param ManagerInterface $auth
     * @param string $name
     * @param array $children
     */
    private function removePermission($auth, $name, $children = array()) {
        $permission = $auth->getPermission($name);
        $auth->removeChildren($permission);
        if ($children)
            foreach ($children as $childName => $childOptions) {
                $this->removePermission($auth, $childName,
                    isset($childOptions['children']) ? $childOptions['children'] : array());
            }
        $auth->remove($permission);
    }
}
