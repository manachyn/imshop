<?php

namespace im\search\components;

use im\search\components\index\IndexManager;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use im\search\components\service\SearchServiceInterface;
use im\search\models\IndexAttribute;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;
use yii\helpers\Inflector;

class SearchManager extends Component
{
    /**
     * @var SearchableInterface[]
     */
    private $_searchableTypes = [
        'product' => [
            'class' => 'im\search\components\searchable\SearchableActiveRecord',
            'modelClass' => 'im\catalog\models\Product'
        ]
    ];

    /**
     * @var SearchServiceInterface[]
     */
    public $searchServices = [
        'elastic' => [
            'class' => 'im\elasticsearch\components\SearchService',
            'name' => 'ElasticSearch'
        ]
    ];

    public $_indexManager = 'im\search\components\index\IndexManager';

    public $_searchComponent = 'im\search\components\SearchComponent';

    /**
     * Returns registered searchable types.
     *
     * @return SearchableInterface[]
     */
    public function getSearchableTypes()
    {
        foreach ($this->_searchableTypes as $type => $item) {
            if (!$item instanceof SearchableInterface) {
                $this->_searchableTypes[$type] = Yii::createObject($item);
            }
        }

        return $this->_searchableTypes;
    }

    /**
     * @param array $searchableTypes
     */
    public function setSearchableTypes($searchableTypes)
    {
        $this->_searchableTypes = $searchableTypes;
    }

    /**
     * Returns searchable type.
     *
     * @param string $type
     * @return SearchableInterface
     */
    public function getSearchableType($type)
    {
        if (!isset($this->_searchableTypes[$type])) {
            throw new InvalidParamException("Searchable type '$type' is not registered");
        }
        if (!$this->_searchableTypes[$type] instanceof SearchableInterface) {
            $this->_searchableTypes[$type] = Yii::createObject($this->_searchableTypes[$type]);
        }

        return $this->_searchableTypes[$type];
    }

    public function getSearchableTypeNames()
    {
        $names = array_keys($this->_searchableTypes);

        return array_combine($names, array_map(function ($name) {
            return Inflector::camel2words($name);
        }, $names));
    }

    /**
     * @param string $type
     * @return AttributeDescriptor[]
     */
    public function getSearchableAttributes($type = null)
    {
        $attributes = [];
        foreach ($this->getSearchableTypes() as $itemType => $item) {
            $itemAttributes = $item->getSearchableAttributes();
            if ($type && $itemType === $type) {
                $attributes = $itemAttributes;
                break;
            } else {
                $attributes = array_merge($attributes, $itemAttributes);
            }
        }

        return $attributes;
    }

    /**
     * @param string $type
     * @return IndexAttribute[]
     */
    public function getIndexAttributes($type = null)
    {
        $searchableAttributes = $this->getSearchableAttributes($type);
        $indexAttributes = IndexAttribute::findByIndexType($type);
        $attributes = [];
        foreach ($searchableAttributes as $searchableAttribute) {
            /** @var IndexAttribute $indexAttribute */
            $indexAttribute = null;
            foreach ($indexAttributes as $attribute) {
                if ($attribute->index_type === $searchableAttribute->entity_type && $attribute->name === $searchableAttribute->name) {
                    $indexAttribute = $attribute;
                    break;
                }
            }
            if ($indexAttribute) {
                $indexAttribute->indexable = true;
                $indexAttribute->label = $searchableAttribute->label;
            } else {
                $indexAttribute = new IndexAttribute([
                    'index_type' => $searchableAttribute->entity_type,
                    'name' => $searchableAttribute->name,
                    'label' => $searchableAttribute->label
                ]);
            }
            $attributes[] = $indexAttribute;
        }

        return $attributes;
    }

    /**
     * Returns index manager.
     *
     * @return IndexManager
     */
    public function getIndexManager()
    {
        return $this->_indexManager = Instance::ensure($this->_indexManager, IndexManager::className());
    }

    /**
     * Sets index manager instance or config.
     *
     * @param string|array|IndexManager $indexManager
     */
    public function setIndexManager($indexManager)
    {
        $this->_indexManager = $indexManager;
    }

    /**
     * Returns search component.
     *
     * @return SearchComponent
     */
    public function getSearchComponent()
    {
        return $this->_searchComponent = Instance::ensure($this->_searchComponent, SearchComponent::className());
    }

    /**
     * Sets search component instance or config.
     *
     * @param string|array|SearchComponent $searchComponent
     */
    public function setSearchComponent($searchComponent)
    {
        $this->_searchComponent = $searchComponent;
    }

    /**
     * Returns search service by id.
     *
     * @param string $id
     * @return SearchServiceInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getSearchService($id)
    {
        if (!isset($this->searchServices[$id])) {
            throw new InvalidParamException("The search service '$id' is not registered");
        }
        if (!$this->searchServices[$id] instanceof SearchServiceInterface) {
            $this->searchServices[$id] = Yii::createObject($this->searchServices[$id]);
        }

        return $this->searchServices[$id];
    }



//
//    /**
//     * @param string $entityType
//     * @return AttributeDescriptor[]
//     */
//    public function getSearchableAttributes($entityType = null)
//    {
//        $attributes = [];
//        foreach ($this->searchableItems as $item) {
//            $itemAttributes = $item->getSearchProvider()->getSearchableAttributes();
//            if ($entityType && $item->entityType === $entityType) {
//                $attributes = $itemAttributes;
//                break;
//            } else {
//                $attributes = array_merge($attributes, $itemAttributes);
//            }
//        }
//
//        return $attributes;
//    }
//
//    /**
//     * @return array
//     */
//    public function getSearchableEntityTypes()
//    {
//        return ArrayHelper::map($this->searchableItems, 'entityType', function (SearchableItem $item) {
//            return Inflector::camel2words($item->entityType);
//        });
//    }
//
//    /**
//     * @param string $entityType
//     * @return IndexAttribute[]
//     */
//    public function getIndexAttributes($entityType = null)
//    {
//        $searchableAttributes = $this->getSearchableAttributes($entityType);
//        $indexAttributes = IndexAttribute::findByIndexType($entityType);
//        $attributes = [];
//        foreach ($searchableAttributes as $searchableAttribute) {
//            /** @var IndexAttribute $indexAttribute */
//            $indexAttribute = null;
//            foreach ($indexAttributes as $attribute) {
//                if ($attribute->entity_type === $searchableAttribute->entity_type
//                    && (($attribute->attribute_id && $attribute->attribute_id === $searchableAttribute->attribute_id)
//                    || $attribute->attribute_name === $searchableAttribute->name)) {
//                    $indexAttribute = $attribute;
//                    break;
//                }
//            }
//            if ($indexAttribute) {
//                $indexAttribute->indexable = true;
//                $indexAttribute->name = $searchableAttribute->name;
//                $indexAttribute->label =$searchableAttribute->label;
//            } else {
//                $indexAttribute = new IndexAttribute([
//                    'entity_type' => $searchableAttribute->entity_type,
//                    'attribute_id' => $searchableAttribute->attribute_id,
//                    'attribute_name' => $searchableAttribute->attribute_id ? '' : $searchableAttribute->name,
//                    'name' => $searchableAttribute->name,
//                    'label' => $searchableAttribute->label,
//                ]);
//            }
//            $attributes[] = $indexAttribute;
//        }
//
//        return $attributes;
//    }
//
//    /**
//     * Returns index manager.
//     *
//     * @return IndexManager
//     */
//    public function getIndexManager()
//    {
//        return $this->_indexManager = Instance::ensure($this->_indexManager, IndexManager::className());
//    }
//
//    /**
//     * Sets index manager instance or config.
//     *
//     * @param string|array|IndexManager $indexManager
//     */
//    public function setIndexManager($indexManager)
//    {
//        $this->_indexManager = $indexManager;
//    }
//
//    /**
//     * Returns search service by id.
//     *
//     * @param string $id
//     * @return SearchServiceInterface
//     * @throws \yii\base\InvalidConfigException
//     */
//    public function getSearchService($id)
//    {
//        if (!isset($this->searchServices[$id])) {
//            throw new InvalidParamException("The search service '$id' is not registered");
//        }
//        if (!$this->searchServices[$id] instanceof SearchServiceInterface) {
//            $this->searchServices[$id] = Yii::createObject($this->searchServices[$id]);
//        }
//
//        return $this->searchServices[$id];
//    }
//
//    private function normalizeSearchableTypes($types) {
//        foreach ($types as $key => $type) {
//            $types[$key] = $this->normalizeSearchableType($type);
//        }
//    }
//
//    private function normalizeSearchableType($type)
//    {
//        if (!$type instanceof SearchableInterface) {
//            $type = Yii::createObject($type);
//        }
//
//        return $type;
//    }
}