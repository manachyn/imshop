<?php

use yii\db\Schema;
use yii\db\Migration;

class m150415_113756_create_seo_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Category meta
        $this->createTable(
            '{{%seo_meta}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'entity_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'meta_title' => Schema::TYPE_STRING . '(255) NOT NULL',
                'meta_keywords' => Schema::TYPE_STRING . '(255) NOT NULL',
                'meta_description' => Schema::TYPE_STRING . '(255) NOT NULL',
                'meta_robots' => Schema::TYPE_STRING . '(50) NOT NULL',
                'custom_meta' => Schema::TYPE_TEXT . ' NOT NULL',
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%seo_meta}}', 'entity_id');
        $this->createIndex('entity_type', '{{%seo_meta}}', 'entity_type');

        $this->createTable(
            '{{%social_meta}}',
            [
                'id' => Schema::TYPE_PK,
                'meta_id' => $this->integer()->notNull(),
                'meta_type' => $this->string(100)->notNull(),
                'social_type' => $this->string(100)->notNull(),
                'title' => $this->string(100)->defaultValue(null),
                'type' => $this->string(50)->defaultValue(null),
                'url' => $this->string()->defaultValue(null),
                'image' => $this->string()->defaultValue(null),
                'description' => $this->string()->defaultValue(null),
                'site_name' => $this->string(100)->defaultValue(null),
                'video' => $this->string()->defaultValue(null),
                'card' => $this->string(100)->defaultValue(null),
                'site' => $this->string(100)->defaultValue(null),
                'creator' => $this->string(100)->defaultValue(null),
            ],
            $tableOptions
        );
        $this->createIndex('meta_id', '{{%social_meta}}', 'meta_id');
        $this->createIndex('meta_type', '{{%social_meta}}', 'meta_type');
        $this->createIndex('social_type', '{{%social_meta}}', 'social_type');
    }

    public function down()
    {
        $this->dropTable('{{%social_meta}}');
    }
}
