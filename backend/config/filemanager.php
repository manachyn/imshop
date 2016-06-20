<?php
return [
    'roots' => [
        [
            'path' => '@frontend/uploads',
            'url' => '/uploads',
            'alias' => ['modules/filesystem/module', 'Uploads']
        ],
        [
            'driver' => 'S3',
            'options' => array_merge(require(__DIR__ . '/../../common/config/s3.php'), [
                'alias' => 'S3',
                'glideURL' => '/storage/s3',
                'glideKey' => 'akqTelFIql'
            ])
        ]
    ],
    'filesystems' => ['local' => [
        'glideURL' => '/storage/local',
        'glideKey' => 'akqTelFIql'
    ]/*, 'dropbox', 's3'*/]
];