<?php

namespace im\elasticsearch;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerSearchService($app);
    }

    /**
     * Registers search service.
     *
     * @param Application $app
     */
    public function registerSearchService($app)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = $app->get('searchManager');
        $searchManager->registerSearchService('elastic', [
            'class' => 'im\elasticsearch\components\SearchService',
            'name' => 'ElasticSearch'
        ]);
    }
}