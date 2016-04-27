<?php

namespace im\search\components\service\db;

use im\search\components\index\IndexableInterface;
use im\search\components\index\IndexInterface;
use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;
use im\search\models\IndexAttribute;
use Yii;

/**
 * Searchable type for indexed active record models.
 *
 * @package im\search\components\service\db
 */
class IndexedSearchableType extends SearchableType implements IndexableInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string|array|IndexProviderInterface
     */
    private $_indexProvider = 'im\search\components\index\provider\ActiveRecordIndexProvider';

    /**
     * @var string|array|DocumentToObjectTransformerInterface
     */
    private $_documentToObjectTransformer = 'im\search\components\transformer\DocumentToActiveRecordTransformer';

    /**
     * @var string|array|ObjectToDocumentTransformerInterface
     */
    private $_objectToDocumentTransformer = 'im\search\components\service\db\ActiveRecordToDocumentTransformer';

    /**
     * @var IndexInterface
     */
    private $_index;

    /**
     * @inheritdoc
     */
    public function getSearchService()
    {
        if ($this->searchServiceId) {
            return parent::getSearchService();
        } else {
            return $this->getIndex()->getSearchService();
        }
    }

    /**
     * @inheritdoc
     */
    public function getIndex()
    {
        if (!$this->_index) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            $this->_index = $searchManager->getIndexManager()->getIndexByType($this->getType());
        }

        return $this->_index;
    }

    /**
     * @inheritdoc
     */
    public function getIndexMapping()
    {
        $model = $this->getModel();
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $entityType = $typesRegister->getEntityType($model);
        $indexableAttributes = IndexAttribute::findByIndexType($entityType);
        $searchableAttributes = $this->getSearchableAttributes();
        $attributes = [];
        foreach ($indexableAttributes as $indexableAttribute) {
            foreach ($searchableAttributes as $searchableAttribute) {
                if ($indexableAttribute->name === $searchableAttribute->name) {
                    $name = $searchableAttribute->name;
                    if ($indexableAttribute->type) {
                        $searchableAttribute->type = $indexableAttribute->type;
                    }
                    if ($indexableAttribute->index_name) {
                        $searchableAttribute->name = $indexableAttribute->index_name;
                    }
                    $searchableAttribute->params = [];
                    if ($indexableAttribute->full_text_search) {
                        $searchableAttribute->params['fullTextSearch'] = (bool) $indexableAttribute->full_text_search;
                    }
                    if ($indexableAttribute->boost) {
                        $searchableAttribute->params['boost'] = (int) $indexableAttribute->boost;
                    }
                    if ($indexableAttribute->suggestions) {
                        $searchableAttribute->params['suggestions'] = (bool) $indexableAttribute->suggestions;
                    }
                    $attributes[$name] = $searchableAttribute;
                }
            }
        }

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function getIndexProvider()
    {
        if (!$this->_indexProvider instanceof IndexProviderInterface) {
            if (is_string($this->_indexProvider)) {
                $this->_indexProvider = ['class' => $this->_indexProvider];
            }
            $this->_indexProvider = Yii::createObject($this->_indexProvider);
            $this->_indexProvider->setObjectClass($this->modelClass);
        }

        return $this->_indexProvider;
    }

    /**
     * @inheritdoc
     */
    public function getObjectToDocumentTransformer()
    {
        if (!$this->_objectToDocumentTransformer instanceof DocumentToObjectTransformerInterface) {
            if (is_string($this->_objectToDocumentTransformer)) {
                $this->_objectToDocumentTransformer = ['class' => $this->_objectToDocumentTransformer];
            }
            $this->_objectToDocumentTransformer = Yii::createObject($this->_objectToDocumentTransformer);
        }

        return $this->_objectToDocumentTransformer;
    }

    /**
     * @inheritdoc
     */
    public function getDocumentToObjectTransformer()
    {
        if (!$this->_documentToObjectTransformer instanceof ObjectToDocumentTransformerInterface) {
            if (is_string($this->_documentToObjectTransformer)) {
                $this->_documentToObjectTransformer = ['class' => $this->_documentToObjectTransformer];
            }
            $this->_documentToObjectTransformer = Yii::createObject($this->_documentToObjectTransformer);
            $this->_documentToObjectTransformer->setObjectClass($this->modelClass);
        }

        return $this->_documentToObjectTransformer;
    }

    /**
     * @param DocumentToObjectTransformerInterface|string|array $documentToObjectTransformer
     */
    public function setDocumentToObjectTransformer($documentToObjectTransformer)
    {
        $this->_documentToObjectTransformer = $documentToObjectTransformer;
    }

    /**
     * @param ObjectToDocumentTransformerInterface|string|array $objectToDocumentTransformer
     */
    public function setObjectToDocumentTransformer($objectToDocumentTransformer)
    {
        $this->_objectToDocumentTransformer = $objectToDocumentTransformer;
    }
}