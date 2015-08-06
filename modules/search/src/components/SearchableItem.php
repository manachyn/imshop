<?php

namespace im\search\components;

use Yii;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class SearchableItem extends Object
{
    /**
     * @var string
     */
    public $entityType;

    /**
     * @var SearchProviderInterface|array|string
     */
    private $_searchProvider = 'im\search\components\SearchProvider';

    /**
     * @return SearchProviderInterface
     */
    public function getSearchProvider()
    {
        if (!$this->_searchProvider instanceof SearchProviderInterface) {
            if (is_string($this->_searchProvider)) {
                $this->_searchProvider = ['class' => $this->_searchProvider];
            }
            $this->_searchProvider = ArrayHelper::merge([
                'class' => 'im\search\components\SearchProvider',
            ], $this->_searchProvider);
            if (!isset($this->_searchProvider['modelClass'])) {
                /** @var \im\base\components\EntityTypesRegister $typesRegister */
                $typesRegister = Yii::$app->get('typesRegister');
                $this->_searchProvider['modelClass'] = $typesRegister->getEntityClass($this->entityType);
            }
            $this->_searchProvider = Yii::createObject($this->_searchProvider);
        }

        return $this->_searchProvider;
    }

    /**
     * @param SearchProviderInterface|array|string $searchProvider
     */
    public function setSearchProvider($searchProvider)
    {
        $this->_searchProvider = $searchProvider;
    }
}