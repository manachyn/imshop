<?php

namespace im\search\widgets;

use yii\base\Widget;

/**
 * Class SearchWidget
 * @package im\search\widgets
 */
class SearchWidget extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('search', []);
    }
}