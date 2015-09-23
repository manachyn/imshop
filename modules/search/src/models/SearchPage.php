<?php

namespace im\search\models;

use im\cms\models\Page;
use im\search\components\SearchPageInterface;

class SearchPage extends Page implements SearchPageInterface
{
    const TYPE = 'search_page';
}