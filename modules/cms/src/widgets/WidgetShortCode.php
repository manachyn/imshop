<?php

namespace im\cms\widgets;
use im\cms\models\widgets\Widget;

/**
 * Class WidgetShortCode
 * @package im\cms\widgets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class WidgetShortCode
{
    /**
     * @param array $config
     * @return string
     * @throws \Exception
     */
    public static function run($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget = Widget::findOne($config['id']);
            $out = $widget ? $widget->run() : '';
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return ob_get_clean() . $out;
    }
}