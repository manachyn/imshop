<?php

use yii\db\Schema;
use yii\db\Migration;

class m150806_091325_create_search_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

//        // Indexes
//        $this->createTable('{{%indexes}}', [
//            'id' => $this->primaryKey(),
//            'name' => $this->string(100)->notNull(),
//            'type' => $this->string(100)->notNull(),
//            'service' => $this->string(100)->notNull(),
//            'status' => $this->boolean()->defaultValue(1)
//        ], $tableOptions);
//
//        // Index attributes
//        $this->createTable('{{%index_attributes}}', [
//            'id' => $this->primaryKey(),
//            'index_type' => $this->string(100)->notNull(),
//            'name' => $this->string(100)->notNull(),
//            'type' => $this->string(100)->notNull()
//        ], $tableOptions);
//
////        $this->addForeignKey('FK_index_attributes_attribute_id', '{{%index_attributes}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');
////
////        // Filter sets
////        $this->createTable('{{%filter_sets}}', [
////            'id' => $this->primaryKey(),
////            'name' => $this->string()->notNull()
////        ], $tableOptions);
////
////        // Filters
////        $this->createTable('{{%filters}}', [
////            'id' => $this->primaryKey(),
////            'name' => $this->string()->notNull()
////        ], $tableOptions);
////
////        // Filter set - Filter junction table
////        $this->createTable(
////            '{{%filter_set_filters}}',
////            [
////                'set_id' => Schema::TYPE_INTEGER . ' NOT NULL',
////                'filter_id' => Schema::TYPE_INTEGER . ' NOT NULL'
////
////            ],
////            $tableOptions
////        );
////
////        $this->addForeignKey('FK_filter_set_filters_set_id', '{{%filter_set_filters}}', 'set_id', '{{%filter_sets}}', 'id', 'CASCADE', 'CASCADE');
////        $this->addForeignKey('FK_filter_set_filters_filter_id', '{{%filter_set_filters}}', 'filter_id', '{{%filters}}', 'id', 'CASCADE', 'CASCADE');
//
//
        // Facets
        $this->createTable('{{%facets}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'entity_type' => $this->string(100)->notNull(),
            'attribute_id' => $this->integer()->defaultValue(null),
            'attribute_name' => $this->string(100)->notNull(),
            'from' => $this->string(100)->notNull(),
            'to' => $this->string(100)->notNull(),
            'interval' => $this->string(100)->notNull(),
            'type' => $this->string(100)->notNull()
        ], $tableOptions);

        $this->addForeignKey('FK_facets_attribute_id', '{{%facets}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');

        // Facet ranges
        $this->createTable('{{%facet_ranges}}', [
            'id' => $this->primaryKey(),
            'facet_id' => $this->integer()->notNull(),
            'from' => $this->string()->notNull(),
            'to' => $this->string()->notNull(),
            'from_include' => $this->boolean()->defaultValue(1),
            'to_include' => $this->boolean()->defaultValue(0),
            'display' => $this->string()->notNull(),
            'sort' => $this->integer()->defaultValue(null)
        ], $tableOptions);

        $this->addForeignKey('FK_facet_ranges_facet_id', '{{%facet_ranges}}', 'facet_id', '{{%facets}}', 'id', 'CASCADE', 'CASCADE');

        // Facet terms
        $this->createTable('{{%facet_terms}}', [
            'id' => $this->primaryKey(),
            'facet_id' => $this->integer()->notNull(),
            'term' => $this->string()->notNull(),
            'display' => $this->string()->notNull(),
            'sort' => $this->integer()->defaultValue(null)
        ], $tableOptions);

        $this->addForeignKey('FK_facet_ranges_facet_id', '{{%facet_ranges}}', 'facet_id', '{{%facets}}', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('{{%indexes}}');
        $this->dropTable('{{%index_attributes}}');
//        $this->dropTable('{{%filter_set_filters}}');
//        $this->dropTable('{{%filter_sets}}');
//        $this->dropTable('{{%filters}}');
        $this->dropTable('{{%facets}}');
        $this->dropTable('{{%facet_ranges}}');
    }
}
