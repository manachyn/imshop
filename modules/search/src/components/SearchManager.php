<?php

namespace im\search\components;

use im\base\types\EntityType;
use im\search\components\index\IndexManager;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\search\SearchComponent;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use im\search\components\service\SearchServiceInterface;
use im\search\models\IndexAttribute;
use im\search\models\SearchPage;
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
    private $_searchableTypes = [];

    /**
     * @var SearchServiceInterface[]
     */
    private $_searchServices = [];

    /**
     * @var string|array|IndexManager
     */
    private $_indexManager = 'im\search\components\index\IndexManager';

    /**
     * @var string|array|SearchComponent
     */
    private $_searchComponent = 'im\search\components\search\SearchComponent';

    /**
     * Returns registered searchable types.
     *
     * @return SearchableInterface[]
     */
    public function getSearchableTypes()
    {
        foreach ($this->_searchableTypes as $item) {
            if (!$item instanceof SearchableInterface) {
                /** @var SearchableInterface $item */
                $item = Yii::createObject($item);
                $this->_searchableTypes[$item->getType()] = $item;
            }
        }

        return $this->_searchableTypes;
    }

    /**
     * Sets searchable types.
     *
     * @param array|SearchableInterface[] $searchableTypes
     */
    public function setSearchableTypes($searchableTypes)
    {
        $this->_searchableTypes = $searchableTypes;
    }

    /**
     * Register searchable type.
     *
     * @param string|array|SearchableInterface $type
     */
    public function registerSearchableType($type)
    {
        if (!$type instanceof SearchableInterface) {
            $type = Yii::createObject($type);
        }
        $this->_searchableTypes[$type->getType()] = $type;
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

    /**
     * Returns default searchable type.
     *
     * @return SearchableInterface
     */
    public function getDefaultSearchableType()
    {
        $types = $this->getSearchableTypes();
        foreach ($types as $type) {
            if ($type->isDefault()) {
                return $type;
            }
        }

        return reset($types);
    }

    /**
     * Returns searchable type by class.
     *
     * @param string $class
     * @return SearchableInterface|null
     */
    public function getSearchableTypeByClass($class)
    {
        foreach ($this->getSearchableTypes() as $type) {
            if ($type->getClass() === $class) {
                return $type;
            }
        }

        return null;
    }

    /**
     * Returns searchable types names.
     *
     * @return array
     */
    public function getSearchableTypeNames()
    {
        $names = array_keys($this->_searchableTypes);

        return array_combine($names, array_map(function ($name) {
            return Inflector::camel2words($name);
        }, $names));
    }

    /**
     * Returns registered search services.
     *
     * @return service\SearchServiceInterface[]
     */
    public function getSearchServices()
    {
        foreach ($this->_searchServices as $key => $item) {
            if (!$item instanceof SearchServiceInterface) {
                $this->_searchServices[$key] = Yii::createObject($item);
            }
        }

        return $this->_searchServices;
    }

    /**
     * Sets search services.
     *
     * @param array|SearchServiceInterface[] $searchServices
     */
    public function setSearchServices($searchServices)
    {
        $this->_searchServices = $searchServices;
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
        if (!isset($this->_searchServices[$id])) {
            throw new InvalidParamException("The search service '$id' is not registered");
        }
        if (!$this->_searchServices[$id] instanceof SearchServiceInterface) {
            $this->_searchServices[$id] = Yii::createObject($this->_searchServices[$id]);
        }

        return $this->_searchServices[$id];
    }

    /**
     * Register search service.
     *
     * @param string $id
     * @param string|array|SearchServiceInterface $service
     */
    public function registerSearchService($id, $service)
    {
        $this->_searchServices[$id] = $service;
    }

    /**
     * Returns searchable attributes by type.
     *
     * @param string $type
     * @return AttributeDescriptor[]
     */
    public function getSearchableAttributes($type)
    {
        $attributes = [];
        foreach ($this->getSearchableTypes() as $itemType => $item) {
            $itemAttributes = $item->getSearchableAttributes();
            if ($itemType === $type) {
                $attributes = $itemAttributes;
                break;
            } else {
                $attributes = array_merge($attributes, $itemAttributes);
            }
        }

        return $attributes;
    }

    /**
     * Returns index attributes by type.
     *
     * @param string $type
     * @return IndexAttribute[]
     */
    public function getIndexAttributes($type)
    {
        $searchableAttributes = $this->getSearchableAttributes($type);
        $indexAttributes = IndexAttribute::findByIndexType($type);
        $attributes = [];
        foreach ($searchableAttributes as $searchableAttribute) {
            /** @var IndexAttribute $indexAttribute */
            $indexAttribute = null;
            foreach ($indexAttributes as $attribute) {
                if ($attribute->name === $searchableAttribute->name) {
                    $indexAttribute = $attribute;
                    break;
                }
            }
            if ($indexAttribute) {
                $indexAttribute->indexable = true;
                $indexAttribute->label = $searchableAttribute->label;
            } else {
                $indexAttribute = new IndexAttribute([
                    'index_type' => $type,
                    'name' => $searchableAttribute->name,
                    'label' => $searchableAttribute->label
                ]);
            }
            $attributes[] = $indexAttribute;
        }

        return $attributes;
    }

    /**
     * Create facet instance by type.
     *
     * @param string $type
     * @return FacetInterface
     */
    public function getFacetInstance($type)
    {
        return Yii::createObject(['class' => Yii::$app->get('typesRegister')->getEntityClass($type)]);
    }

    /**
     * Create facet value instance by type.
     *
     * @param string $type
     * @return FacetValueInterface
     */
    public function getFacetValueInstance($type)
    {
        return Yii::createObject(['class' => Yii::$app->get('typesRegister')->getEntityClass($type)]);
    }

    /**
     * Return facet types.
     *
     * @return EntityType[]
     */
    public function getFacetTypes()
    {
        return Yii::$app->get('typesRegister')->getEntityTypes('facets');
    }

    /**
     * Return facet value types.
     *
     * @return EntityType[]
     */
    public function getFacetValueTypes()
    {
        return Yii::$app->get('typesRegister')->getEntityTypes('facet_values');
    }

    /**
     * Get search page url.
     *
     * @return string
     */
    public function getSearchPageUrl()
    {
        /** @var \im\cms\components\PageFinder $finder */
        $finder = Yii::$app->get('pageFinder');
        $page = $finder->findModel(['type' => SearchPage::TYPE, 'status' => SearchPage::STATUS_PUBLISHED]);

        return $page ? $page->getUrl() : '';
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
}