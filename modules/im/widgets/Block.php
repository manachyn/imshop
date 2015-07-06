<?php

namespace im\base\widgets;

use yii\base\Widget;

class Block extends Widget
{
    private $_content;

    /**
     * Starts recording a block.
     */
    public function init()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        $block = ob_get_clean();
        $this->_content = $block;
    }

    public function __toString()
    {
        return $this->_content;
    }
}
