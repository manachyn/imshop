<?php

namespace im\seo\frontend;

use im\base\types\EntityType;
use im\seo\models\Meta;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\View;

/**
 * Class Bootstrap
 * @package im\seo
 */
class Bootstrap extends \im\seo\Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerEventHandlers($app);
    }

    /**
     * Registers module event handlers.
     *
     * @param \yii\base\Application $app
     */
    public function registerEventHandlers($app)
    {
        $app->view->on(View::EVENT_BEGIN_PAGE, function (Event $event) use ($app) {
            /** @var \im\seo\components\Seo $seo */
            $seo = $app->get('seo');
            /** @var View $view */
            $view = $event->sender;
            $seo->applyContextMetaToView($view);
        });
    }
}