<?php

namespace im\shortcodes;

/**
 * Class Shortcode
 * @package im\shortcodes
 * @author Ivan Manachyn
 */
class Shortcode extends \tpoxa\shortcodes\Shortcode
{
    /**
     * Register new shortcode.
     * @param string $name
     * @param callable|string $callback
     * @return $this
     */
    public function register($name, $callback)
    {
        $this->callbacks[$name] = $callback;

        return $this;
    }
}
