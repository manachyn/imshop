<?php

namespace im\search\components\query\facet;

class TreeFacetHelper
{
    /**
     * @param EntityFacetValueInterface $parent
     * @param EntityFacetValueInterface[] $values
     * @param int $depth
     * @return EntityFacetValueInterface[]
     */
    public static function getChildren($parent, $values, $depth = null)
    {
        $children = array_filter($values, function (EntityFacetValueInterface $value) use ($parent, $depth) {
            return $value->getEntity()->{$value->getEntity()->leftAttribute} > $parent->getEntity()->{$parent->getEntity()->leftAttribute}
            && $value->getEntity()->{$value->getEntity()->rightAttribute} < $parent->getEntity()->{$parent->getEntity()->rightAttribute} && ($depth === null
                || $value->getEntity()->{$value->getEntity()->depthAttribute} <= $parent->getEntity()->{$parent->getEntity()->depthAttribute} + $depth)
            && ($parent->getEntity()->treeAttribute === false || $value->{$value->getEntity()->treeAttribute} === $parent->getEntity()->{$parent->getEntity()->treeAttribute});
        });
        usort($children, function (EntityFacetValueInterface $value1, EntityFacetValueInterface $value2) {
            return $value1->getEntity()->{$value1->getEntity()->leftAttribute} > $value2->getEntity()->{$value2->getEntity()->leftAttribute};
        });

        return $children;
    }

    /**
     * @param EntityFacetValueInterface $parent
     * @param EntityFacetValueInterface[] $values
     */
    public static function buildValueTree($parent, $values)
    {
        $children = self::getChildren($parent, $values, 1);
        foreach ($children as $key => $value) {
            self:: buildValueTree($value, $values);
        }
        /** @var TreeFacetValueInterface $parent */
        $parent->setChildren($children);
    }

    /**
     * @param EntityFacetValueInterface[] $values
     * @return TreeFacetValueInterface[]
     */
    public static function buildValuesTree($values)
    {
        $roots = self::getRootValues($values);
        foreach ($roots as $key => $value) {
            self::buildValueTree($value, $values);
        }

        return $roots;
    }

    /**
     * @param EntityFacetValueInterface[] $values
     * @return EntityFacetValueInterface[]
     */
    public static function getRootValues($values)
    {
        $depth = self::getMinDepth($values);
        $values = array_filter($values, function (EntityFacetValueInterface $value) use ($depth) { return $value->getEntity()->{$value->getEntity()->depthAttribute} === $depth; });
        usort($values, function (EntityFacetValueInterface $value1, EntityFacetValueInterface $value2) {
            return $value1->getEntity()->{$value1->getEntity()->leftAttribute} > $value2->getEntity()->{$value2->getEntity()->leftAttribute};
        });

        return $values;
    }

    /**
     * @param EntityFacetValueInterface[] $values
     * @return int
     */
    public static function getMinDepth($values)
    {
        return min(array_map(function (EntityFacetValueInterface $value) { return $value->getEntity()->{$value->getEntity()->depthAttribute}; }, $values));
    }
}