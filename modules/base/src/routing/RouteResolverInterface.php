<?php

namespace im\base\routing;

interface RouteResolverInterface
{
    /**
     * Resolve route by params.
     *
     * @param array $params request params
     * @return string|boolean the resolved route, or false if route cannot be resolved
     */
    public function resolve($params);

    /**
     * Returns route which can be resolved.
     *
     * @return string
     */
    public function getRoute();
}