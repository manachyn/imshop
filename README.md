http://www.yiiframework.com/wiki/799/yii2-app-advanced-on-single-domain-apache-nginx/
http://mickgeek.com/yii-2-advanced-template-on-the-same-domain-2

http://www.elisdn.ru/blog/85/seo-service-on-yii2-reusing-of-modules
https://github.com/ElisDN/seokeys/tree/master/modules/user/widgets/backend/grid
http://www.elisdn.ru/blog/78/yii2-codeception-testing


cd /path/to/project/frontend/web
ln -s ../../backend/web backend

chmod -R 0777 frontend/runtime
chmod -R 0777 backend/runtime
chmod -R 0777 frontend/web/assets
chmod -R 0777 backend/web/assets