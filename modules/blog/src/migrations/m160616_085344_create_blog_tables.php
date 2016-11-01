<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation for blog tables.
 */
class m160616_085344_create_blog_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Article files
        $this->createTable(
            '{{%article_files}}',
            [
                'id' => $this->primaryKey(),
                'filesystem' => $this->string(100)->notNull(),
                'path' => $this->string()->notNull(),
                'title' =>$this->string()->defaultValue(null),
                'size' => $this->integer(),
                'mime_type' => $this->string(100)->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Articles
        $this->createTable(
            '{{%articles}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'slug' => $this->string(100)->notNull(),
                'announce' => $this->text()->defaultValue(null),
                'content' => $this->text()->defaultValue(null),
                'status' => $this->boolean()->defaultValue(0),
                'image_id' => $this->integer()->defaultValue(null),
                'video_id' => $this->integer()->defaultValue(null),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        $this->createIndex('slug', '{{%articles}}', 'slug');
        $this->createIndex('status', '{{%articles}}', 'status');
        $this->createIndex('created_at', '{{%articles}}', 'created_at');
        $this->createIndex('updated_at', '{{%articles}}', 'updated_at');
        $this->addForeignKey('FK_articles_image_id', '{{%articles}}', 'image_id', '{{%article_files}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_articles_video_id', '{{%articles}}', 'video_id', '{{%article_files}}', 'id', 'SET NULL', 'CASCADE');


        // News
        $this->createTable(
            '{{%news}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'slug' => $this->string(100)->notNull(),
                'announce' => $this->text()->defaultValue(null),
                'content' => $this->text()->defaultValue(null),
                'status' => $this->boolean()->defaultValue(0),
                'image_id' => $this->integer()->defaultValue(null),
                'video_id' => $this->integer()->defaultValue(null),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        $this->createIndex('slug', '{{%news}}', 'slug');
        $this->createIndex('status', '{{%news}}', 'status');
        $this->createIndex('created_at', '{{%news}}', 'created_at');
        $this->createIndex('updated_at', '{{%news}}', 'updated_at');
        $this->addForeignKey('FK_news_image_id', '{{%news}}', 'image_id', '{{%article_files}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_news_video_id', '{{%news}}', 'video_id', '{{%article_files}}', 'id', 'SET NULL', 'CASCADE');

        // Article meta
        $this->createTable(
            '{{%article_meta}}',
            [
                'id' => $this->primaryKey(),
                'entity_id' => $this->integer()->defaultValue(null),
                'entity_type' => $this->string(100)->notNull(),
                'meta_title' => $this->string()->defaultValue(null),
                'meta_keywords' => $this->string()->defaultValue(null),
                'meta_description' => $this->string()->defaultValue(null),
                'meta_robots' => $this->string(50)->defaultValue(null),
                'custom_meta' => $this->text()->defaultValue(null)
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%article_meta}}', 'entity_id');
        $this->createIndex('entity_type', '{{%article_meta}}', 'entity_type');

        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->addColumn('{{%widgets}}', 'display_count', $this->integer()->defaultValue(null));
            $this->addColumn('{{%widgets}}', 'category_id', $this->integer()->defaultValue(null));
            $this->addColumn('{{%widgets}}', 'columns', $this->integer()->defaultValue(null));
            $this->addColumn('{{%widgets}}', 'template', $this->integer()->defaultValue(null));
            $this->createIndex('category_id', '{{%widgets}}', 'category_id');
        }

        if ($this->db->schema->getTableSchema('{{%pages}}', true)) {
            $this->addColumn('{{%pages}}', 'category_id', $this->integer()->defaultValue(null));
        }

        // Article categories
        $this->createTable(
            '{{%article_categories}}',
            [
                'id' => $this->primaryKey(),
                'tree' => $this->integer()->notNull(),
                'lft' => $this->integer()->notNull(),
                'rgt' => $this->integer()->notNull(),
                'depth' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'slug' => $this->string()->notNull(),
                'status' => $this->boolean()->defaultValue(1),
                'created_at' => $this->integer()->defaultValue(null),
                'updated_at' => $this->integer()->defaultValue(null),
            ],
            $tableOptions
        );

        $this->createIndex('lft_rgt', '{{%article_categories}}', 'lft, rgt');
        $this->createIndex('depth', '{{%article_categories}}', 'depth');
        $this->createIndex('tree', '{{%article_categories}}', 'tree');

        // Article categories pivot table
        $this->createTable('{{%articles_categories}}', [
            'article_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ], $tableOptions);
        $this->addForeignKey('FK_articles_categories_article_id', '{{%articles_categories}}', 'article_id', '{{%articles}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_articles_categories_category_id', '{{%articles_categories}}', 'category_id', '{{%article_categories}}', 'id', 'CASCADE', 'CASCADE');


        // News categories
        $this->createTable(
            '{{%news_categories}}',
            [
                'id' => $this->primaryKey(),
                'tree' => $this->integer()->notNull(),
                'lft' => $this->integer()->notNull(),
                'rgt' => $this->integer()->notNull(),
                'depth' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'slug' => $this->string()->notNull(),
                'status' => $this->boolean()->defaultValue(1),
                'created_at' => $this->integer()->defaultValue(null),
                'updated_at' => $this->integer()->defaultValue(null),
            ],
            $tableOptions
        );

        $this->createIndex('lft_rgt', '{{%news_categories}}', 'lft, rgt');
        $this->createIndex('depth', '{{%news_categories}}', 'depth');
        $this->createIndex('tree', '{{%news_categories}}', 'tree');

        // News categories pivot table
        $this->createTable('{{%news_categories_pivot}}', [
            'news_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull()
        ], $tableOptions);
        $this->addForeignKey('FK_news_categories_pivot_article_id', '{{%news_categories_pivot}}', 'news_id', '{{%news}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_news_categories_pivot_category_id', '{{%news_categories_pivot}}', 'category_id', '{{%news_categories}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%article_meta}}');
        $this->dropTable('{{%article_files}}');
        $this->dropTable('{{%articles}}');
        $this->dropTable('{{%news}}');
        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->dropColumn('{{%widgets}}', 'display_count');
            $this->dropColumn('{{%widgets}}', 'category_id');
            $this->dropColumn('{{%widgets}}', 'columns');
            $this->dropColumn('{{%widgets}}', 'template');
        }
        if ($this->db->schema->getTableSchema('{{%pages}}', true)) {
            $this->dropColumn('{{%pages}}', 'category_id');
        }
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
