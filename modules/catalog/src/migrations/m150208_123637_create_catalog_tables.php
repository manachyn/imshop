<?php

use yii\db\Schema;
use yii\db\Migration;

class m150208_123637_create_catalog_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Category files
        $this->createTable(
            '{{%category_files}}',
            [
                'id' => Schema::TYPE_PK,
                'filesystem' => Schema::TYPE_STRING . '(100) NOT NULL',
                'path' => Schema::TYPE_STRING . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'size' => Schema::TYPE_INTEGER,
                'mime_type' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER,
                'updated_at' => Schema::TYPE_INTEGER
            ],
            $tableOptions
        );

        // Categories
        $this->createTable(
            '{{%categories}}',
            [
                'id' => Schema::TYPE_PK,
                'tree' => Schema::TYPE_INTEGER,
                'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
                'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
                'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
                'name' => Schema::TYPE_STRING . '(255) NOT NULL',
                'slug' => Schema::TYPE_STRING . '(255) NOT NULL',
                'description' => Schema::TYPE_STRING . '(255) NOT NULL',
                'image_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 1',
                'template_id' => $this->integer()->defaultValue(null),
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );

        $this->createIndex('lft_rgt', '{{%categories}}', 'lft, rgt');
        $this->createIndex('depth', '{{%categories}}', 'depth');
        $this->createIndex('tree', '{{%categories}}', 'tree');
        $this->createIndex('name', '{{%categories}}', 'name');
        $this->createIndex('description', '{{%categories}}', 'description');
        $this->addForeignKey('FK_categories_image_id', '{{%categories}}', 'image_id', '{{%category_files}}', 'id', 'SET NULL', 'CASCADE');

        // Category meta
        $this->createTable(
            '{{%category_meta}}',
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
        $this->addForeignKey('FK_category_meta_entity_id', '{{%category_meta}}', 'entity_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');

        // Product category files
        $this->createTable(
            '{{%product_category_files}}',
            [
                'id' => Schema::TYPE_PK,
                'filesystem' => Schema::TYPE_STRING . '(100) NOT NULL',
                'path' => Schema::TYPE_STRING . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'size' => Schema::TYPE_INTEGER,
                'mime_type' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER,
                'updated_at' => Schema::TYPE_INTEGER
            ],
            $tableOptions
        );

        // Product categories
        $this->createTable(
            '{{%product_categories}}',
            [
                'id' => Schema::TYPE_PK,
                'tree' => Schema::TYPE_INTEGER,
                'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
                'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
                'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
                'name' => Schema::TYPE_STRING . '(255) NOT NULL',
                'slug' => Schema::TYPE_STRING . '(255) NOT NULL',
                'description' => Schema::TYPE_STRING . '(255) NOT NULL',
                'image_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 1',
                'template_id' => $this->integer()->defaultValue(null),
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );

        $this->createIndex('lft_rgt', '{{%product_categories}}', 'lft, rgt');
        $this->createIndex('depth', '{{%product_categories}}', 'depth');
        $this->createIndex('tree', '{{%product_categories}}', 'tree');
        $this->createIndex('name', '{{%product_categories}}', 'name');
        $this->createIndex('description', '{{%product_categories}}', 'description');
        $this->addForeignKey('FK_product_categories_image_id', '{{%product_categories}}', 'image_id', '{{%product_category_files}}', 'id', 'SET NULL', 'CASCADE');

        // Product category meta
        $this->createTable(
            '{{%product_category_meta}}',
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
        $this->addForeignKey('FK_product_category_meta_entity_id', '{{%product_category_meta}}', 'entity_id', '{{%product_categories}}', 'id', 'CASCADE', 'CASCADE');

        // Brands
        $this->createTable(
            '{{%brands}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );

        // Product types
        $this->createTable(
            '{{%product_types}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'parent_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL'

            ],
            $tableOptions
        );
        $this->addForeignKey('FK_product_types_parent_id', '{{%product_types}}', 'parent_id', '{{%product_types}}', 'id', 'SET NULL', 'CASCADE');

        // Product type - Attribute junction table
        $this->createTable(
            '{{%product_type_attributes}}',
            [
                'product_type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER . ' NOT NULL'

            ],
            $tableOptions
        );
        //$this->addPrimaryKey('PK_product_type_attributes', '{{%product_type_attributes}}', 'product_type_id, attribute_id');
        $this->addForeignKey('FK_product_type_attributes_product_type_id', '{{%product_type_attributes}}', 'product_type_id', '{{%product_types}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_product_type_attributes_attribute_id', '{{%product_type_attributes}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');

        // Product type - Option junction table
        $this->createTable(
            '{{%product_type_options}}',
            [
                'product_type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'option_id' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        //$this->addPrimaryKey('PK_product_type_options', '{{%product_type_options}}', 'product_type_id, option_id');
        $this->addForeignKey('FK_product_type_options_product_type', '{{%product_type_options}}', 'product_type_id', '{{%product_types}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_product_type_options_option', '{{%product_type_options}}', 'option_id', '{{%options}}', 'id', 'CASCADE', 'CASCADE');

        // Products
        $this->createTable(
            '{{%products}}',
            [
                'id' => Schema::TYPE_PK,
                'sku' => Schema::TYPE_STRING . '(100) NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'slug' => Schema::TYPE_STRING . '(100) NOT NULL',
                'description' => Schema::TYPE_STRING . ' NOT NULL',
                'quantity' => Schema::TYPE_STRING . ' NOT NULL',
                'price' => Schema::TYPE_DECIMAL . ' NOT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'brand_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'type_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'available_on' => Schema::TYPE_INTEGER . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_products_brand_id', '{{%products}}', 'brand_id', '{{%brands}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('FK_products_type_id', '{{%products}}', 'type_id', '{{%product_types}}', 'id', 'SET NULL', 'CASCADE');

        // Product meta
        $this->createTable(
            '{{%product_meta}}',
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
        $this->addForeignKey('FK_product_meta_entity_id', '{{%product_meta}}', 'entity_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');

        // Product - Category junction table
        $this->createTable(
            '{{%products_categories}}',
            [
                'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'category_id' => Schema::TYPE_INTEGER . ' NOT NULL'

            ],
            $tableOptions
        );
        //$this->addPrimaryKey('PK_products_categories', '{{%product_category}}', 'product_id, category_id');
        $this->addForeignKey('FK_products_categories_product_id', '{{%products_categories}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_products_categories_category_id', '{{%products_categories}}', 'category_id', '{{%product_categories}}', 'id', 'CASCADE', 'CASCADE');

        // Product attribute values
        $this->createTable(
            '{{%eav_product_values}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'attribute_name' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'attribute_type' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'string_value' => Schema::TYPE_STRING . '(255) NOT NULL',
                'value_entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_eav_product_values_entity_id', '{{%eav_product_values}}', 'entity_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_eav_product_values_attribute_id', '{{%eav_product_values}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('attribute_name', '{{%eav_product_values}}', 'attribute_name');
        $this->createIndex('string_value', '{{%eav_product_values}}', 'string_value');
        $this->createIndex('value_entity_id', '{{%eav_product_values}}', 'value_entity_id');

        // Product option values
        $this->createTable(
            '{{%product_option_values}}',
            [
                'id' => Schema::TYPE_PK,
                'option_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'value' => Schema::TYPE_STRING . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_product_option_values_option_id', '{{%product_option_values}}', 'option_id', '{{%options}}', 'id', 'CASCADE', 'CASCADE');

        // Product variants
        $this->createTable(
            '{{%product_variants}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'presentation' => Schema::TYPE_STRING . ' NOT NULL',
                'sku' => Schema::TYPE_STRING . '(100) NOT NULL',
                'price' => Schema::TYPE_DECIMAL . ' NOT NULL',
                'master' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_product_variants_entity_id', '{{%product_variants}}', 'entity_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');

        // Product variant - Option value junction table
        $this->createTable(
            '{{%product_variant_option_values}}',
            [
                'product_variant_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'option_value_id' => Schema::TYPE_INTEGER . ' NOT NULL'

            ],
            $tableOptions
        );
        //$this->addPrimaryKey('PK_product_variant_option_values', '{{%product_variant_option_values}}', 'product_variant_id, option_value_id');
        $this->addForeignKey('FK_product_variant_option_values_product_variant_id', '{{%product_variant_option_values}}', 'product_variant_id', '{{%product_variants}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_product_variant_option_values_option_value_id', '{{%product_variant_option_values}}', 'option_value_id', '{{%product_option_values}}', 'id', 'CASCADE', 'CASCADE');

        // Product files
        $this->createTable(
            '{{%product_files}}',
            [
                'id' => Schema::TYPE_PK,
                'product_id' => Schema::TYPE_INTEGER,
                'attribute' => Schema::TYPE_STRING . '(100) NOT NULL',
                'filesystem' => Schema::TYPE_STRING . '(100) NOT NULL',
                'path' => Schema::TYPE_STRING . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'size' => Schema::TYPE_INTEGER,
                'mime_type' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER,
                'updated_at' => Schema::TYPE_INTEGER,
                'sort' => Schema::TYPE_INTEGER
            ],
            $tableOptions
        );

        $this->addForeignKey('FK_product_files_product_id', '{{%product_files}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('attribute', '{{%product_files}}', 'attribute');
        $this->createIndex('sort', '{{%product_files}}', 'sort');


        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->addColumn('{{%widgets}}', 'depth', Schema::TYPE_INTEGER);
        }
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%categories}}');
        $this->dropTable('{{%category_meta}}');
        $this->dropTable('{{%product_categories}}');
        $this->dropTable('{{%product_category_meta}}');
        $this->dropTable('{{%brands}}');
        $this->dropTable('{{%product_types}}');
        $this->dropTable('{{%product_type_attributes}}');
        $this->dropTable('{{%product_type_options}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%product_meta}}');
        $this->dropTable('{{%products_categories}}');
        $this->dropTable('{{%eav_product_values}}');
        $this->dropTable('{{%product_option_values}}');
        $this->dropTable('{{%product_variants}}');
        $this->dropTable('{{%product_variant_option_values}}');
        $this->dropTable('{{%product_files}}');
        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->dropColumn('{{%widgets}}', 'depth');
        }
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
