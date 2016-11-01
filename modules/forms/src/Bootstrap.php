<?php

namespace im\forms;

use im\cms\widgets\FormWidget;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\cms
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
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
        //$shortcodes = $app->get('shortcodes');
        //$shortcodes->register('form', [FormWidget::class, 'widget']);
    }
}
