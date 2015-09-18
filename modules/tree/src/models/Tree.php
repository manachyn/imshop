<?php

namespace im\tree\models;

use creocoder\nestedsets\NestedSetsBehavior;
use im\tree\components\TreeRecursiveIterator;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Tree model base class.
 *
 * @method boolean makeRoot(boolean $runValidation = true, array $attributes = null)
 * @method boolean prependTo(ActiveRecord $node, boolean $runValidation = true, array $attributes = null)
 * @method boolean appendTo(ActiveRecord $node, boolean $runValidation = true, array $attributes = null)
 * @method boolean insertBefore(ActiveRecord $node, boolean $runValidation = true, array $attributes = null)
 * @method boolean insertAfter(ActiveRecord $node, boolean $runValidation = true, array $attributes = null)
 * @method integer|false deleteWithChildren()
 * @method ActiveQuery parents(integer $depth = null)
 * @method ActiveQuery children(integer $depth = null)
 * @method ActiveQuery leaves()
 * @method ActiveQuery prev()
 * @method ActiveQuery next()
 * @method boolean isRoot()
 * @method boolean isChildOf(ActiveRecord $node)
 * @method boolean isLeaf()
 * @method void beforeInsert()
 * @method void afterInsert()
 * @method void beforeUpdate()
 * @method void afterUpdate()
 * @method void beforeDelete()
 * @method void afterDelete()
 */
abstract class Tree extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new TreeQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['tree'], $fields['lft'], $fields['rgt'], $fields['depth']);
        $fields['hasChildren'] = function ($model) {
            /** @var Tree $model */
            return $model->children(1)->count() > 0;
        };
        return $fields;
    }

    /**
     * @param Tree $node
     * @param array $items
     * @return TreeRecursiveIterator
     */
    public static function buildNodeTree($node, $items = [])
    {
        $items = $items ?: $node->children()->all();
        $tree = self::buildTree($items, $node->{$node->leftAttribute}, $node->{$node->rightAttribute});

        return $tree;
    }

    /**
     * @param Tree[] $nodes
     * @param int $left
     * @param int $right
     * @return array
     */
    public static function buildTree($nodes, $left = 0, $right = null) {
        $tree = [];
        foreach ($nodes as $key => $node) {
            if ($node->{$node->leftAttribute} == $left + 1 && (is_null($right) || $node->{$node->rightAttribute} < $right)) {
                $tree[$key] = $node;
                if ($node->{$node->rightAttribute} - $node->{$node->leftAttribute} > 1) {
                    $node->populateRelation('children', self::buildTree($nodes, $node->{$node->leftAttribute}, $node->{$node->rightAttribute}));
                } else {
                    $node->populateRelation('children', []);
                }
                $left = $node->{$node->rightAttribute};
            }
        }

        return $tree;
    }
} 