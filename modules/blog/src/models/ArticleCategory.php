<?php

namespace im\blog\models;

use im\catalog\models\Category;

/**
 * Class ArticleCategory
 * @package im\blog\models
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class ArticleCategory extends Category
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['status'], 'safe']
        ];
    }
}
