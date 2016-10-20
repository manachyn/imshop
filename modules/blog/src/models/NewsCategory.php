<?php

namespace im\blog\models;

/**
 * Class NewsCategory
 * @package im\blog\models
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class NewsCategory extends ArticleCategory
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_categories}}';
    }
}
