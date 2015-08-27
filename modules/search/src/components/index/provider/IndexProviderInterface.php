<?php

namespace im\search\components\index\provider;

interface IndexProviderInterface
{
    public function countObjects($offset = 0);

    public function getObjects($limit = null, $offset = 0);

    public function getModelClass();

    public function setModelClass($modelClass);
}