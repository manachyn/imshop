http://www.yiiframework.com/wiki/799/yii2-app-advanced-on-single-domain-apache-nginx/
http://mickgeek.com/yii-2-advanced-template-on-the-same-domain-2
http://www.xpertdeveloper.com/2015/08/yii2-setup-advance-application/

http://www.elisdn.ru/blog/85/seo-service-on-yii2-reusing-of-modules
https://github.com/ElisDN/seokeys/tree/master/modules/user/widgets/backend/grid
http://www.elisdn.ru/blog/78/yii2-codeception-testing


cd /path/to/project/frontend/web
ln -s ../../backend/web admin
cd /path/to/project/backend/web
ln -s ../../frontend/web/files files
ln -s ../../frontend/web/uploads uploads
ln -s ../../frontend/web/test-files test-files // For tests

chmod -R 0777 frontend/runtime
chmod -R 0777 backend/runtime
chmod -R 0777 frontend/web/assets
chmod -R 0777 backend/web/assets
chmod -R 0777 frontend/web/files
chmod -R 0777 frontend/web/uploads 


composer global require "codeception/codeception=2.0.*"
composer global require "codeception/specify=*"
composer global require "codeception/verify=*"
   
~/.composer/vendor/bin/codecept run

~/.composer/vendor/bin/codecept run acceptance TemplatesCest:testCRUD
~/.composer/vendor/bin/codecept run functional MenusCest

Selenium
composer global require se/selenium-server-standalone
~/.composer/vendor/bin/selenium-server-standalone

php -S localhost:8080

sudo rm -rf ../../../../../backend/web/assets/*

## Build frontend assets

```
php yii webpack/webpack frontend/assets/compression/webpack.php
```

npm install
webpack
webpack-dev-server


Migrations
php yii migrate --migrationPath=@im/config/migrations
php yii migrate --migrationPath=@im/cms/migrations
php yii migrate --migrationPath=@im/seo/migrations
php yii migrate --migrationPath=@im/eav/migrations
php yii migrate --migrationPath=@im/variation/migrations
php yii migrate --migrationPath=@im/catalog/migrations // Only after eav and variation
php yii migrate --migrationPath=@im/blog/migrations // Only after cms