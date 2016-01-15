<?php

namespace im\search\widgets;

use yii\base\Widget;

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