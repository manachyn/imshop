<?php

namespace app\modules\rbac\commands;

use app\modules\rbac\rules\AuthorRule;
use yii\console\Controller;
use Yii;


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
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Rules
        $authorRule = new AuthorRule();
        $auth->add($authorRule);

        // Permissions
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Can access backend';
        $auth->add($accessBackend);

        $administrateRbac = $auth->createPermission('administrateRbac');
        $administrateRbac->description = 'Can administrate all "RBAC" module';
        $auth->add($administrateRbac);

        $BackendViewRoles = $auth->createPermission('BackendViewRoles');
        $BackendViewRoles->description = 'Can view roles list';
        $auth->add($BackendViewRoles);

        $BackendCreateRoles = $auth->createPermission('BackendCreateRoles');
        $BackendCreateRoles->description = 'Can create roles';
        $auth->add($BackendCreateRoles);

        $BackendUpdateRoles = $auth->createPermission('BackendUpdateRoles');
        $BackendUpdateRoles->description = 'Can update roles';
        $auth->add($BackendUpdateRoles);

        $BackendDeleteRoles = $auth->createPermission('BackendDeleteRoles');
        $BackendDeleteRoles->description = 'Can delete roles';
        $auth->add($BackendDeleteRoles);

        $BackendViewPermissions = $auth->createPermission('BackendViewPermissions');
        $BackendViewPermissions->description = 'Can view permissions list';
        $auth->add($BackendViewPermissions);

        $BackendCreatePermissions = $auth->createPermission('BackendCreatePermissions');
        $BackendCreatePermissions->description = 'Can create permissions';
        $auth->add($BackendCreatePermissions);

        $BackendUpdatePermissions = $auth->createPermission('BackendUpdatePermissions');
        $BackendUpdatePermissions->description = 'Can update permissions';
        $auth->add($BackendUpdatePermissions);

        $BackendDeletePermissions = $auth->createPermission('BackendDeletePermissions');
        $BackendDeletePermissions->description = 'Can delete permissions';
        $auth->add($BackendDeletePermissions);

        $BackendViewRules = $auth->createPermission('BackendViewRules');
        $BackendViewRules->description = 'Can view rules list';
        $auth->add($BackendViewRules);

        $BackendCreateRules = $auth->createPermission('BackendCreateRules');
        $BackendCreateRules->description = 'Can create rules';
        $auth->add($BackendCreateRules);

        $BackendUpdateRules = $auth->createPermission('BackendUpdateRules');
        $BackendUpdateRules->description = 'Can update rules';
        $auth->add($BackendUpdateRules);

        $BackendDeleteRules = $auth->createPermission('BackendDeleteRules');
        $BackendDeleteRules->description = 'Can delete rules';
        $auth->add($BackendDeleteRules);

        // Assignments
        $auth->addChild($administrateRbac, $BackendViewRoles);
        $auth->addChild($administrateRbac, $BackendCreateRoles);
        $auth->addChild($administrateRbac, $BackendUpdateRoles);
        $auth->addChild($administrateRbac, $BackendDeleteRoles);
        $auth->addChild($administrateRbac, $BackendViewPermissions);
        $auth->addChild($administrateRbac, $BackendCreatePermissions);
        $auth->addChild($administrateRbac, $BackendUpdatePermissions);
        $auth->addChild($administrateRbac, $BackendDeletePermissions);
        $auth->addChild($administrateRbac, $BackendViewRules);
        $auth->addChild($administrateRbac, $BackendCreateRules);
        $auth->addChild($administrateRbac, $BackendUpdateRules);
        $auth->addChild($administrateRbac, $BackendDeleteRules);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $user);

        $superadmin = $auth->createRole('superadmin');
        $superadmin->description = 'Super admin';
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
        $auth->addChild($superadmin, $accessBackend);
        $auth->addChild($superadmin, $administrateRbac);

        $auth->assign($superadmin, 1);
    }
}
