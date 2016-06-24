<?php

return [
    'local' => [
        'class' => 'im\filesystem\components\flysystem\LocalFilesystem',
        'path' => '@www/files'
    ],
    's3' => array_merge(['class' => 'im\filesystem\components\flysystem\AwsS3Filesystem'], require(__DIR__ . '/s3.php'))
];
