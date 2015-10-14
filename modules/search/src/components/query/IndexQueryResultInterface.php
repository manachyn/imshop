<?php

namespace im\search\components\query;

interface IndexQueryResultInterface
{
    /**
     * @return array
     */
    public function getDocuments();
}