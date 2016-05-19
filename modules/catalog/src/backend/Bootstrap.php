<?php

namespace im\catalog\backend;

use im\base\routing\ModuleRulesTrait;

/**
 * Class Bootstrap
 * @package im\catalog\backend
 */
class Bootstrap extends \im\catalog\Bootstrap
{
    use ModuleRulesTrait;

    /**
     * Module routing.
     *
     * @return array
     */
    public function getRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD roots' => 'roots',
                    'GET,HEAD leaves' => 'leaves',
                    'GET,HEAD {id}/children' => 'children',
                    'GET,HEAD {id}/descendants' => 'descendants',
                    'GET,HEAD {id}/leaves' => 'leaves',
                    'GET,HEAD {id}/ancestors' => 'ancestors',
                    'GET,HEAD {id}/parent' => 'parent',
                    'PUT,PATCH {id}/move' => 'move',
                    'POST search' => 'search'
                ],
                'controller' => ['categories' => 'catalog/rest/category', 'product-categories' => 'catalog/rest/product-category']
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD {id}/attributes' => 'attributes'
                ],
                'controller' => ['product-types' => 'catalog/rest/product-type']
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->addRules($app);
    }
}
