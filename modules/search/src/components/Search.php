<?php

namespace im\search\components;

use im\search\models\EntityAttribute;
use im\search\models\IndexAttribute;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

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

    /**
     * @param string $entityType
     * @return EntityAttribute[]
     */
    public function getSearchableAttributes($entityType = null)
    {
        $attributes = [];
        foreach ($this->searchableItems as $item) {
            $itemAttributes = $item->getSearchProvider()->getSearchableAttributes();
            if ($entityType && $item->entityType === $entityType) {
                $attributes = $itemAttributes;
                break;
            } else {
                $attributes = array_merge($attributes, $itemAttributes);
            }
        }

        return $attributes;
    }

    /**
     * @return array
     */
    public function getSearchableEntityTypes()
    {
        return ArrayHelper::map($this->searchableItems, 'entityType', function (SearchableItem $item) {
            return Inflector::camel2words($item->entityType);
        });
    }

    /**
     * @param string $entityType
     * @return IndexAttribute[]
     */
    public function getIndexAttributes($entityType = null)
    {
        $searchableAttributes = $this->getSearchableAttributes($entityType);
        $indexAttributes = IndexAttribute::findByEntityType($entityType);
        $attributes = [];
        foreach ($searchableAttributes as $searchableAttribute) {
            /** @var IndexAttribute $indexAttribute */
            $indexAttribute = null;
            foreach ($indexAttributes as $attribute) {
                if ($attribute->entity_type === $searchableAttribute->entity_type
                    && (($attribute->attribute_id && $attribute->attribute_id === $searchableAttribute->attribute_id)
                    || $attribute->attribute_name === $searchableAttribute->name)) {
                    $indexAttribute = $attribute;
                    break;
                }
            }
            if ($indexAttribute) {
                $indexAttribute->indexable = true;
                $indexAttribute->name = $searchableAttribute->name;
                $indexAttribute->label =$searchableAttribute->label;
            } else {
                $indexAttribute = new IndexAttribute([
                    'entity_type' => $searchableAttribute->entity_type,
                    'attribute_id' => $searchableAttribute->attribute_id,
                    'name' => $searchableAttribute->name,
                    'label' => $searchableAttribute->label,
                ]);
            }
            $attributes[] = $indexAttribute;
        }

        return $attributes;
    }
}