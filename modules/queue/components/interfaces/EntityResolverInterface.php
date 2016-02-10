<?php

namespace app\modules\queue\components\interfaces;

interface EntityResolverInterface
{
    /**
     * Resolve the entity for the given ID.
     *
     * @param string $type
     * @param mixed $id
     * @return mixed
     */
    public function resolve($type, $id);
}
