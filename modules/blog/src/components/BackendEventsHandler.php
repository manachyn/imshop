<?php

namespace im\blog\components;

use im\blog\models\Article;
use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\db\AfterSaveEvent;

/**
 * Class BackendEventsHandler
 * @package im\blog\components
 */
class BackendEventsHandler extends Component
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(Article::className(), Article::EVENT_AFTER_UPDATE, [$this, 'onArticleUpdate']);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onArticleUpdate(AfterSaveEvent $event)
    {
        /** @var Article $article */
        $article = $event->sender;
        $this->invalidateCache([get_class($article). '::' . $article->getPrimaryKey()]);
    }

    /**
     * Invalidate tagged cache.
     *
     * @param array $tags
     * @throws \yii\base\InvalidConfigException
     */
    private function invalidateCache(array $tags)
    {
//        /** @var \im\cms\components\Cms $cms */
//        $cms = Yii::$app->get('cms');
//        $cacheManager = $cms->getCacheManager();
//        if ($cacheManager) {
//            $cacheManager->deleteFromCacheByTags($tags);
//        }
    }
}