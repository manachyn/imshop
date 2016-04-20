<?php

namespace im\search\components;

use im\search\components\index\IndexableInterface;
use im\search\components\query\Term;
use im\search\components\service\IndexSearchServiceInterface;
use im\search\models\IndexAttribute;
use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

/**
 * Class EventsHandler
 * @package im\search\components
 */
class EventsHandler extends Component
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [$this, 'onActiveRecordUpdate']);
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'onActiveRecordUpdate']);
        //Event::on(IndexAttribute::className(), IndexAttribute::EVENT_AFTER_INSERT, [$this, 'onIndexMappingChanged']);
        //Event::on(IndexAttribute::className(), IndexAttribute::EVENT_AFTER_UPDATE, [$this, 'onIndexMappingChanged']);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onActiveRecordUpdate(AfterSaveEvent $event)
    {
        $searchManager = $this->getSearchManager();
        /** @var ActiveRecord $model */
        $model = $event->sender;
        if (($searchableType = $searchManager->getSearchableTypeByClass(get_class($model)))
            && $searchableType instanceof IndexableInterface) {
            $index = $searchableType->getIndex();
            $searchService = $searchableType->getSearchService();
            if ($index && $searchService instanceof IndexSearchServiceInterface) {
                $indexer = $searchService->getIndexer();
                $indexer->insertObject($index, $searchableType->getType(), $model);
            }
        }
        // Reindex dependent
        //foreach ($searchManager->getSearchableTypes() as $searchableType) {
        //    if ($searchableType instanceof IndexableInterface) {
        //        $searchService = $searchableType->getSearchService();
        //        $finder = $searchService->getFinder();
        //        $mapping = $searchableType->getIndexMapping();
        //        foreach ($mapping as $name => $attribute) {
        //            if ($attribute->dependency && $attribute->dependency->class == get_class($model)) {
        //                //if ($attribute->dependency->attribute && array_key_exists($attribute->dependency->attribute, $event->changedAttributes)) {
        //                    $related = $finder->findByQuery($searchableType->getType(), new Term('brand', 'Apple'))->result();
        //                    $a = 1;
        //                //}
        //            }
        //        }
        //    }
        //}
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onIndexMappingChanged(AfterSaveEvent $event)
    {
        $searchManager = $this->getSearchManager();
        /** @var IndexAttribute $attribute */
        $attribute = $event->sender;
        $type = $attribute->index_type;

        $searchableType = $searchManager->getSearchableType($type);
        if ($searchableType instanceof IndexableInterface) {
            $mapping = $searchableType->getIndexMapping();
            $index = $searchManager->getIndexManager()->getIndexByType($type);
            if ($index && $mapping) {
                $searchService = $index->getSearchService();
                if ($searchService instanceof IndexSearchServiceInterface) {
                    $indexer = $searchService->getIndexer();
                    $indexer->setMapping($index, $type, $mapping);
                }
            }
        }
    }

    /**
     * @return \im\search\components\SearchManager
     */
    private function getSearchManager()
    {
        return $searchManager = Yii::$app->get('searchManager');
    }
}