Add to vendor/composer/autoload_psr4.php
'im\\base\\' => array($baseDir . '/modules/base/src'),
'im\\forms\\' => array($baseDir . '/modules/forms/src'),
'im\\config\\' => array($baseDir . '/modules/config/src'),
'im\\adminlte\\' => array($baseDir . '/themes/adminlte/src'),
'im\\cms\\' => array($baseDir . '/modules/cms/src'),
'im\\users\\' => array($baseDir . '/modules/users/src'),
'im\\mailer\\' => array($baseDir . '/modules/mailer/src'),
'im\\catalog\\' => array($baseDir . '/modules/catalog/src'),
'im\\tree\\' => array($baseDir . '/modules/tree/src'),
'im\\eav\\' => array($baseDir . '/modules/eav/src'),
'im\\variation\\' => array($baseDir . '/modules/variation/src'),
'im\\seo\\' => array($baseDir . '/modules/seo/src'),
'im\\filesystem\\' => array($baseDir . '/modules/filesystem/src'),
'im\\search\\' => array($baseDir . '/modules/search/src'),
'im\\elasticsearch\\' => array($baseDir . '/modules/elasticsearch/src'),
'im\\image\\' => array($baseDir . '/modules/image/src'),
'im\\fsm\\' => array($baseDir . '/modules/fsm/src'),
'im\\elfinder\\' => array($baseDir . '/modules/elfinder/src'),
'im\\imshop\\' => array($baseDir . '/themes/imshop/src'),
'im\\image\\' => array($baseDir . '/modules/image/src'),



'im\\filesystem\\' => array($baseDir . '/modules/filesystem/src'),
'im\\thruway\\' => array($baseDir . '/modules/thruway/src'),
'im\\ratchet\\' => array($baseDir . '/modules/ratchet/src'),
'im\\events\\' => array($baseDir . '/modules/events/src'),
'im\\messaging\\' => array($baseDir . '/modules/messaging/src'),

Add to vendor/composer/autoload_classmap.php
'elFinderVolumeFlysystem' => $baseDir . '/modules/elfinder/elFinderVolumeFlysystem.php',
'elFinderVolumeS3' => $baseDir . '/modules/elfinder/elFinderVolumeS3.php',

yii migrate --migrationPath=@app/modules/users/src/migrations

php yii migrate --migrationPath=@app/modules/forms/src/migrations
php yii migrate --migrationPath=@app/modules/eav/src/migrations
php yii migrate --migrationPath=@app/modules/variation/src/migrations
php yii migrate --migrationPath=@app/modules/seo/src/migrations
php yii migrate --migrationPath=@app/modules/filesystem/src/migrations
php yii migrate --migrationPath=@app/modules/catalog/src/migrations
php yii migrate --migrationPath=@app/modules/search/src/migrations
php yii migrate --migrationPath=@app/modules/cms/src/migrations

/home/ubuntu/.composer/vendor/bin/codecept build
/home/ubuntu/.composer/vendor/bin/codecept run


Dynamic relations
https://github.com/synatree/yii2-dynamic-relations
