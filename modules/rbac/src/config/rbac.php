<?php

use \yii\rbac\Item;

return [
    'items' => [
        'viewRoles' => [Item::TYPE_PERMISSION, 'View roles'],
        'createRole' => [Item::TYPE_PERMISSION, 'Create role'],
        'updateRole' => [Item::TYPE_PERMISSION, 'Update role'],
        'deleteRole' => [Item::TYPE_PERMISSION, 'Delete role'],
        'viewPermissions' => [Item::TYPE_PERMISSION, 'View permissions'],
        'createPermission' => [Item::TYPE_PERMISSION, 'Create permission'],
        'updatePermission' => [Item::TYPE_PERMISSION, 'Update permission'],
        'deletePermission' => [Item::TYPE_PERMISSION, 'Delete permission'],
        'manageRBAC' => [Item::TYPE_PERMISSION, 'Manage RBAC', [
            'viewRoles',
            'createRole',
            'updateRole',
            'deleteRole',
            'viewPermissions',
            'createPermission',
            'updatePermission',
            'deletePermission'
        ]],
        'admin' => [Item::TYPE_ROLE, [
            'manageRBAC'
        ]]
    ],
    'rules' => [
        'im\rbac\rules\GroupRule',
        'im\rbac\rules\UserRule',
        'im\rbac\rules\AuthorRule'
    ]
];