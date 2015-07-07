<?php

namespace app\modules\users\commands;

use Yii;
use yii\console\Controller;

/**
 * Users RBAC controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'add';

    /**
     * @var array Main module permission array
     */
    public $mainPermission = [
        'name' => 'administrateUsers',
        'description' => 'Can administrate all "Users" module'
    ];

    /**
     * @var array Permission
     */
    public $permissions = [
        'BackendViewUsers' => [
            'description' => 'Can view backend users list'
        ],
        'BackendCreateUsers' => [
            'description' => 'Can create backend users'
        ],
        'BackendUpdateUsers' => [
            'description' => 'Can update backend users'
        ],
        'BackendDeleteUsers' => [
            'description' => 'Can delete backend users'
        ],
        'viewUsers' => [
            'description' => 'Can view users list'
        ],
        'createUsers' => [
            'description' => 'Can create users'
        ],
        'updateUsers' => [
            'description' => 'Can update users'
        ],
        'updateOwnUsers' => [
            'description' => 'Can update own user profile',
            'rule' => 'author'
        ],
        'deleteUsers' => [
            'description' => 'Can delete users'
        ],
        'deleteOwnUsers' => [
            'description' => 'Can delete own user profile',
            'rule' => 'author'
        ]
    ];

    /**
     * Add comments RBAC.
     */
    public function actionAdd()
    {
        $auth = Yii::$app->authManager;
        $superadmin = $auth->getRole('superadmin');
        $mainPermission = $auth->createPermission($this->mainPermission['name']);
        if (isset($this->mainPermission['description'])) {
            $mainPermission->description = $this->mainPermission['description'];
        }
        if (isset($this->mainPermission['rule'])) {
            $mainPermission->ruleName = $this->mainPermission['rule'];
        }
        $auth->add($mainPermission);

        foreach ($this->permissions as $name => $option) {
            $permission = $auth->createPermission($name);
            if (isset($option['description'])) {
                $permission->description = $option['description'];
            }
            if (isset($option['rule'])) {
                $permission->ruleName = $option['rule'];
            }
            $auth->add($permission);
            $auth->addChild($mainPermission, $permission);
        }

        $auth->addChild($superadmin, $mainPermission);

        $updateUsers = $auth->getPermission('updateUsers');
        $updateOwnUsers = $auth->getPermission('updateOwnUsers');
        $deleteUsers = $auth->getPermission('deleteUsers');
        $deleteOwnUsers = $auth->getPermission('deleteOwnUsers');

        $auth->addChild($updateUsers, $updateOwnUsers);
        $auth->addChild($deleteUsers, $deleteOwnUsers);

        return static::EXIT_CODE_NORMAL;
    }

    /**
     * Remove comments RBAC.
     */
    public function actionRemove()
    {
        $auth = Yii::$app->authManager;
        $permissions = array_keys($this->permissions);

        foreach ($permissions as $name => $option) {
            $permission = $auth->getPermission($name);
            $auth->remove($permission);
        }

        $mainPermission = $auth->getPermission($this->mainPermission['name']);
        $auth->remove($mainPermission);

        return static::EXIT_CODE_NORMAL;
    }
}
