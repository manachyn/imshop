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
                'content' => $this->text()->notNull(),
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
                'announce' => $this->string()->notNull(),
                'content' => $this->text()->notNull(),
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
                'meta_title' => $this->string()->notNull(),
                'meta_keywords' => $this->string()->notNull(),
                'meta_description' => $this->string()->notNull(),
                'meta_robots' => $this->string(50)->notNull(),
                'custom_meta' => $this->text()->notNull()
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%article_meta}}', 'entity_id');
        $this->createIndex('entity_type', '{{%article_meta}}', 'entity_type');

        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->addColumn('{{%widgets}}', 'display_count', $this->integer()->defaultValue(null));
        }
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
        }
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
