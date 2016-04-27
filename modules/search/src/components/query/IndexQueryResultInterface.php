<?php

namespace im\search\components\query;

/**
 * Interface IndexQueryResultInterface
 * @package im\search\components\query
 */
interface IndexQueryResultInterface
{
    /**
     * @return array
     */
    public function getDocuments();
}