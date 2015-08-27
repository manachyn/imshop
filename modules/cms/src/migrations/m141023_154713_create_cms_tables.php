<?php

use yii\db\Schema;
use yii\db\Migration;

class m141023_154713_create_cms_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Pages
        $this->createTable(
            '{{%pages}}',
            [
                'id' => Schema::TYPE_PK,
                'type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'title' => Schema::TYPE_STRING . '(100) NOT NULL',
                'slug' => Schema::TYPE_STRING . '(100) NOT NULL',
                'content' => Schema::TYPE_TEXT . ' NOT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'layout_id' => Schema::TYPE_STRING . '(50) NOT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('type', '{{%pages}}', 'type');
        $this->createIndex('slug', '{{%pages}}', 'slug');
        $this->createIndex('created_at', '{{%pages}}', 'created_at');
        $this->createIndex('updated_at', '{{%pages}}', 'updated_at');

        // Page meta
        $this->createTable(
            '{{%page_meta}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'meta_title' => Schema::TYPE_STRING . '(70) NOT NULL',
                'meta_description' => Schema::TYPE_STRING . '(160) NOT NULL',
                'meta_robots' => Schema::TYPE_STRING . '(50) NOT NULL',
                'custom_meta' => Schema::TYPE_TEXT . ' NOT NULL',
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_page_meta_entity_id', '{{%page_meta}}', 'entity_id', '{{%pages}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable(
            '{{%menus}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL'
            ],
            $tableOptions
        );

        $this->createTable(
            '{{%menus_items}}',
            [
                'id' => Schema::TYPE_PK,
                'menu_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'label' => Schema::TYPE_STRING . '(100) NOT NULL',
                'url' => Schema::TYPE_STRING . '(100) NOT NULL',
                'page_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
            ],
            $tableOptions
        );

        // Indexes
        $this->createIndex('status', '{{%menus_items}}', 'status');

        // Foreign Keys
        $this->addForeignKey('FK_menu_item_page', '{{%menus_items}}', 'page_id', '{{%pages}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_menu_item_menu', '{{%menus_items}}', 'menu_id', '{{%menus}}', 'id', 'CASCADE', 'CASCADE');


        // Content widget
        $this->createTable(
            '{{%content_widgets}}',
            [
                'id' => Schema::TYPE_PK,
                'content' => Schema::TYPE_TEXT . ' NOT NULL'
            ],
            $tableOptions
        );

        // Banner widget
        $this->createTable(
            '{{%banner_widgets}}',
            [
                'id' => Schema::TYPE_PK,
                'banner_id' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );

        // Foreign Keys
        //$this->addForeignKey('FK_banner_widget_banner', '{{%banner_widgets}}', 'banner_id', '{{%pages}}', 'id', 'CASCADE', 'CASCADE');

        // Templates
        $this->createTable(
            '{{%templates}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'layout_id' => Schema::TYPE_STRING . '(50) NOT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('layout_id', '{{%templates}}', 'layout_id');

        // Widget Area Config
        $this->createTable(
            '{{%widget_areas}}',
            [
                'id' => Schema::TYPE_PK,
                'code' => Schema::TYPE_STRING . '(50) NOT NULL',
                'template_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'owner_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'owner_type' => Schema::TYPE_STRING . '(50) NOT NULL',
                'display' => 'tinyint(1) NOT NULL DEFAULT 1',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_widget_areas_template_id', '{{%widget_areas}}', 'template_id', '{{%templates}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('code', '{{%widget_areas}}', 'code');
        $this->createIndex('owner_id', '{{%widget_areas}}', 'owner_id');
        $this->createIndex('updated_at', '{{%widget_areas}}', 'updated_at');

        // Widget owner
        $this->createTable(
            '{{%widgets}}',
            [
                'id' => Schema::TYPE_PK,
                'widget_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'widget_type' => Schema::TYPE_STRING . '(50) NOT NULL',
                'owner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'owner_type' => Schema::TYPE_STRING . '(50) NOT NULL',
                'widget_area_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'sort' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );

        // Foreign Keys
        //$this->addForeignKey('FK_widget_owner', '{{%widget_owner}}', 'owner_id', '{{%pages}}', 'id', 'CASCADE', 'CASCADE');
        //$this->addForeignKey('FK_banner_widget_widget_area', '{{%widget_owner}}', 'widget_area_id', '{{%widget_areas}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('widget_id', '{{%widgets}}', 'widget_id');
        $this->createIndex('widget_type', '{{%widgets}}', 'widget_type');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%widgets}}');
        $this->dropTable('{{%widget_areas}}');
        $this->dropTable('{{%templates}}');
        $this->dropTable('{{%banner_widgets}}');
        $this->dropTable('{{%content_widgets}}');
        $this->dropTable('{{%menus_items}}');
        $this->dropTable('{{%menus}}');
        $this->dropTable('{{%page_meta}}');
        $this->dropTable('{{%pages}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
