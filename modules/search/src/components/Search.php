<?php

namespace im\search\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;

class Search extends Component
{
    /**
     * @var SearchableItem[]
     */
    public $searchableItems = [
        ['entityType' => 'product']
    ];

    public $servers = [
        'elastic' => [
            'class' => 'im\search\components\Server',
            'name' => 'ElasticSearch'
        ],
        'database' => [
            'class' => 'im\search\components\Server',
            'name' => 'Database'
        ]
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->searchableItems as $key => $item) {
            $item['class'] = 'im\search\components\SearchableItem';
            $this->searchableItems[$key] = Yii::createObject($item);
        }
        foreach ($this->servers as $key => $server) {
            $this->servers[$key] = Instance::ensure($server, Server::className());
        }
    }

    /**
     * Returns search provider by item type.
     *
     * @param $type
     * @return SearchProviderInterface
     */
    public function getSearchProvider($type)
    {
        return $this->getSearchableItem($type)->getSearchProvider();
    }

    /**
     * Returns searchable item by type.
     *
     * @param string $type
     * @return SearchableItem
     */
    public function getSearchableItem($type)
    {
        foreach ($this->searchableItems as $item) {
            if ($item->entityType === $type) {
                return $item;
            }
        }

        throw new InvalidParamException("Searchable item with type '$type' is not registered");
    }
}