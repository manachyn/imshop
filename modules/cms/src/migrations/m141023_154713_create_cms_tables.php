<?php

use yii\db\Schema;
use yii\db\Migration;

class m141023_154713_create_cms_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Pages
        $this->createTable(
            '{{%pages}}',
            [
                'id' => $this->primaryKey(),
                'tree' => Schema::TYPE_INTEGER,
                'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
                'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
                'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
                'type' => $this->string(100)->notNull(),
                'title' => $this->string(255)->notNull(),
                'slug' => $this->string(100)->notNull(),
                'content' => $this->text()->notNull(),
                'status' => $this->boolean()->defaultValue(0),
                'template_id' => $this->integer()->defaultValue(null),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );
        $this->createIndex('lft_rgt', '{{%pages}}', 'lft, rgt');
        $this->createIndex('depth', '{{%pages}}', 'depth');
        $this->createIndex('tree', '{{%pages}}', 'tree');
        $this->createIndex('slug', '{{%pages}}', 'slug');
        $this->createIndex('status', '{{%pages}}', 'status');
        $this->createIndex('type', '{{%pages}}', 'type');
        $this->createIndex('created_at', '{{%pages}}', 'created_at');
        $this->createIndex('updated_at', '{{%pages}}', 'updated_at');

        // Page meta
        $this->createTable(
            '{{%page_meta}}',
            [
                'id' => $this->primaryKey(),
                'entity_id' => $this->integer()->defaultValue(null),
                'meta_title' => $this->string()->notNull(),
                'meta_keywords' => $this->string()->notNull(),
                'meta_description' => $this->string()->notNull(),
                'meta_robots' => $this->string(50)->notNull(),
                'custom_meta' => $this->text()->notNull()
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_page_meta_entity_id', '{{%page_meta}}', 'entity_id', '{{%pages}}', 'id', 'CASCADE', 'CASCADE');

        // Menus
        $this->createTable(
            '{{%menus}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(100)->notNull(),
                'location' => $this->string(100)->notNull()
            ],
            $tableOptions
        );

        // Indexes
        $this->createIndex('location', '{{%menus}}', 'location');

        // Menu item files
        $this->createTable(
            '{{%menu_item_files}}',
            [
                'id' => $this->primaryKey(),
                'menu_item_id' => $this->integer()->defaultValue(null),
                'attribute' => $this->string(100)->notNull(),
                'filesystem' => $this->string(100)->notNull(),
                'path' => $this->string()->notNull(),
                'title' => $this->string()->notNull(),
                'size' => $this->integer(),
                'mime_type' => $this->string()->notNull(),
                'created_at' => $this->integer()->defaultValue(null),
                'updated_at' => $this->integer()->defaultValue(null)
            ],
            $tableOptions
        );

        // Menu items
        $this->createTable(
            '{{%menu_items}}',
            [
                'id' => $this->primaryKey(),
                'tree' => $this->integer()->defaultValue(null),
                'lft' => $this->integer()->notNull(),
                'rgt' => $this->integer()->notNull(),
                'depth' => $this->integer()->notNull(),
                'menu_id' => $this->integer()->notNull(),
                'label' => $this->string()->notNull(),
                'title' => $this->string()->notNull(),
                'url' => $this->string()->notNull(),
                'target_blank' => $this->boolean()->defaultValue(0),
                'css_classes' => $this->string(100)->notNull(),
                'rel' => $this->string(100)->notNull(),
                'status' => $this->boolean()->defaultValue(1),
                'visibility' => $this->string(100)->notNull(),
                'items_display' => $this->boolean(),
                'items_css_classes' => $this->string(100)->notNull(),
                'icon_id' => $this->integer()->defaultValue(null),
                'active_icon_id' => $this->integer()->defaultValue(null),
                'video_id' => $this->integer()->defaultValue(null)
            ],
            $tableOptions
        );

        // Indexes
        $this->createIndex('lft_rgt', '{{%menu_items}}', 'lft, rgt');
        $this->createIndex('depth', '{{%menu_items}}', 'depth');
        $this->createIndex('tree', '{{%menu_items}}', 'tree');
        $this->createIndex('name', '{{%menu_items}}', 'label');
        $this->createIndex('menu_id_status', '{{%menu_items}}', ['menu_id', 'status']);
        // Foreign Keys
        $this->addForeignKey('FK_menu_items_menu_id', '{{%menu_items}}', 'menu_id', '{{%menus}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_menu_items_icon_id', '{{%menu_items}}', 'icon_id', '{{%menu_item_files}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_menu_items_active_icon_id', '{{%menu_items}}', 'active_icon_id', '{{%menu_item_files}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_menu_items_video_id', '{{%menu_items}}', 'video_id', '{{%menu_item_files}}', 'id', 'SET NULL', 'CASCADE');


        // Templates
        $this->createTable(
            '{{%templates}}',
            [
                'id' => $this->primaryKey(),
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'layout_id' => Schema::TYPE_STRING . '(50) NOT NULL',
                'default' => $this->boolean()->defaultValue(0)
            ],
            $tableOptions
        );
        $this->createIndex('layout_id', '{{%templates}}', 'layout_id');
        $this->createIndex('default', '{{%templates}}', 'default');

        // Widget Area
        $this->createTable(
            '{{%widget_areas}}',
            [
                'id' => $this->primaryKey(),
                'code' => $this->string(50)->notNull(),
                'template_id' => $this->integer()->defaultValue(null),
                'owner_id' => $this->integer()->defaultValue(null),
                'owner_type' => $this->string(100)->notNull(),
                'display' => $this->boolean()->defaultValue(1),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('FK_widget_areas_template_id', '{{%widget_areas}}', 'template_id', '{{%templates}}', 'id', 'CASCADE', 'CASCADE');

        // Indexes
        $this->createIndex('code', '{{%widget_areas}}', 'code');
        $this->createIndex('owner_id', '{{%widget_areas}}', 'owner_id');
        $this->createIndex('owner_type', '{{%widget_areas}}', 'owner_type');
        $this->createIndex('updated_at', '{{%widget_areas}}', 'updated_at');

        // Widget
        $this->createTable(
            '{{%widgets}}',
            [
                'id' => $this->primaryKey(),
                'widget_type' => $this->string(100)->notNull(),
                'content' => $this->text()->notNull(),
                'banner_id' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Widget area - Widgets
        $this->createTable(
            '{{%widget_area_widgets}}',
            [
                'id' => $this->primaryKey(),
                'widget_id' => $this->integer()->notNull(),
                'widget_area_id' => $this->integer()->notNull(),
                'owner_id' => $this->integer()->defaultValue(null),
                'owner_type' => $this->string(100)->notNull(),
                'sort' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('FK_widget_area_widgets_widget_id', '{{%widget_area_widgets}}', 'widget_id', '{{%widgets}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_widget_area_widgets_widget_area_id', '{{%widget_area_widgets}}', 'widget_area_id', '{{%widget_areas}}', 'id', 'CASCADE', 'CASCADE');

        // Indexes
        $this->createIndex('sort', '{{%widget_area_widgets}}', 'sort');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%widget_area_widgets}}');
        $this->dropTable('{{%widgets}}');
        $this->dropTable('{{%widget_areas}}');
        $this->dropTable('{{%templates}}');
        $this->dropTable('{{%menu_items}}');
        $this->dropTable('{{%menus}}');
        $this->dropTable('{{%page_meta}}');
        $this->dropTable('{{%pages}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
