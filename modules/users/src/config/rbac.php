<?php

use \yii\rbac\Item;

return [
    'items' => [
        'viewUsers' => [Item::TYPE_PERMISSION, 'View users'],
        'createUser' => [Item::TYPE_PERMISSION, 'Create user'],
        'updateUser' => [Item::TYPE_PERMISSION, 'Update user'],
        'deleteUser' => [Item::TYPE_PERMISSION, 'Delete user'],
        'manageUsers' => [Item::TYPE_PERMISSION, 'Manage users', [
            'viewUsers',
            'createUser',
            'updateUser',
            'deleteUser'
        ]],
        'admin' => [Item::TYPE_ROLE, [
            'manageUsers'
        ]]
    ]
];