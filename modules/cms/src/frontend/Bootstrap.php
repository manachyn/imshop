<?php

namespace im\cms\frontend;

use im\cms\widgets\WidgetShortCode;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\cms
 */
class Bootstrap extends \im\cms\Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $this->registerShortcodes($app);
    }

    /**
     * Register shortcodes
     *
     * @param Application $app
     */
    public function registerShortcodes($app)
    {
        /** @var \im\shortcodes\Shortcode $shortcodes */
        $shortcodes = $app->get('shortcodes');
        $shortcodes->register('widget', [WidgetShortCode::class, 'run']);
    }
}
