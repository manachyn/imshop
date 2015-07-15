'im\\base\\' => array($baseDir . '/modules/base/src'),
'im\\config\\' => array($baseDir . '/modules/config/src'),
'im\\adminlte\\' => array($baseDir . '/themes/adminlte/src'),
'im\\cms\\' => array($baseDir . '/modules/cms/src'),
'im\\users\\' => array($baseDir . '/modules/users/src'),
'im\\mailer\\' => array($baseDir . '/modules/mailer/src'),

yii migrate --migrationPath=@app/modules/users/src/migrations

/home/ubuntu/.composer/vendor/bin/codecept build
/home/ubuntu/.composer/vendor/bin/codecept run
