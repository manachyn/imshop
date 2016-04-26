<?php

namespace im\tree\components;

use creocoder\nestedsets\NestedSetsBehavior;
use im\tree\models\Tree;

class TreeHelper
{
    /**
     * @param Tree|NestedSetsBehavior $parent
     * @param Tree[]|NestedSetsBehavior[] $nodes
     * @param int $depth
     * @return Tree[]
     */
    public static function getChildren($parent, $nodes, $depth = null)
    {
        $children = array_filter($nodes, function ($node) use ($parent, $depth) {
            return $node->{$node->leftAttribute} > $parent->{$parent->leftAttribute}
            && $node->{$node->rightAttribute} < $parent->{$parent->rightAttribute} && ($depth === null
                || $node->{$node->depthAttribute} <= $parent->{$parent->depthAttribute} + $depth)
            && ($parent->treeAttribute === false || $node->{$node->treeAttribute} === $parent->{$parent->treeAttribute});
        });
        usort($children, function ($node1, $node2) {
            return $node1->{$node1->leftAttribute} > $node2->{$node2->leftAttribute};
        });

        return $children;
    }

    /**
     * @param Tree|NestedSetsBehavior $parent
     * @param Tree[]|NestedSetsBehavior[] $nodes
     */
    public static function buildNodeTree($parent, $nodes)
    {
        $children = self::getChildren($parent, $nodes, 1);
        foreach ($children as $key => $node) {
            self:: buildNodeTree($node, $nodes);
        }
        $parent->populateRelation('children', $children);
    }

    /**
     * @param Tree[]|NestedSetsBehavior[] $nodes
     * @return Tree[]
     */
    public static function buildNodesTree($nodes)
    {
        $roots = self::getRootNodes($nodes);
        foreach ($roots as $key => $node) {
            self::buildNodeTree($node, $nodes);
        }

        return $roots ;
    }

    /**
     * @param Tree[] $nodes
     * @return Tree[]|NestedSetsBehavior[]
     */
    public static function getRootNodes($nodes)
    {
        $depth = self::getMinDepth($nodes);

        return array_filter($nodes, function ($node) use ($depth) { return $node->{$node->depthAttribute} === $depth; });
    }

    /**
     * @param Tree[] $nodes
     * @return int
     */
    public static function getMinDepth($nodes)
    {
        return min(array_map(function ($node) { return $node->{$node->depthAttribute}; }, $nodes));
    }
}