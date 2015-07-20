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
                'meta_title' => Schema::TYPE_STRING . '(70) NOT NULL',
                'meta_description' => Schema::TYPE_STRING . '(160) NOT NULL',
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
                'meta_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'meta_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'social_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'title' => Schema::TYPE_STRING . '(100) NOT NULL',
                'type' => Schema::TYPE_STRING . '(50) NOT NULL',
                'url' => Schema::TYPE_STRING . '(255) NOT NULL',
                'image' => Schema::TYPE_STRING . '(255) NOT NULL',
                'description' => Schema::TYPE_STRING . '(255) NOT NULL',
                'site_name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'video' => Schema::TYPE_STRING . '(255) NOT NULL',
                'card' => Schema::TYPE_STRING . '(100) NOT NULL',
                'site' => Schema::TYPE_STRING . '(100) NOT NULL',
                'creator' => Schema::TYPE_STRING . '(100) NOT NULL'
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
