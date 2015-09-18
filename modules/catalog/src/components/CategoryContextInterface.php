<?php

namespace im\catalog\components;

use im\catalog\models\Category;

interface CategoryContextInterface
{
    /**
     * @return Category
     */
    public function getCategory();
}