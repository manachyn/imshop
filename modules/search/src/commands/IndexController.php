<?php

namespace im\search\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class IndexController extends Controller
{
    /**
     * @var int Start indexing at offset
     */
    public $offset = 0;

    /**
     * @var int Sleep time between iterations (microseconds)
     */
    public $sleep = 0;

    /**
     * @var int Index packet size
     */
    public $batchSize;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'reindex';

    /**
     * Reindex objects by index name and type.
     *
     * @param string $index Index name
     * @param string $type Index type
     * @return int Exit code
     */
    public function actionReindex($index, $type)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $index = $searchManager->getIndexManager()->getIndex($index);
        if ($index) {
            $indexer = $index->getSearchService()->getIndexer();
            $options = [
                'offset' => $this->offset,
                'sleep' => $this->sleep,
                'batchSize' => $this->batchSize
            ];
            $progress = $options['batchSize'] ? $this->getProgress('Reindex: ') : null;
            $result = $indexer->reindex($index, $type, $options, $progress);

            $this->stdout("Total: {$result->total}\n");
            $this->stdout("Indexed: {$result->success}\n");
            $this->stdout("Error: {$result->error}\n");
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionSearch($index, $type)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $index = $searchManager->getIndexManager()->getIndex($index);
        $finder = $index->getSearchService()->getFinder();
        $query = $finder->find($index->getName(), $type);
        $res = $query->where(['eAttributes.type' => 'semiautomatic'])->addFacet('eAttributes.type', 'terms', ['field' => 'eAttributes.type'])->result();
        $objects = $res->getObjects();

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Returns progress closure.
     *
     * @param string $action
     * @return callable
     */
    private function getProgress($action) {
        $progress = false;
        return function ($processed, $total, $message = null) use ($progress, $action) {
            if (!$progress) {
                Console::startProgress(0, $total, $action);
            }
            if ($message) {
                Console::endProgress($message . PHP_EOL, false);
            } else {
                Console::updateProgress($processed, $total);
            }
        };
    }
} 