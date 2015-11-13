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

        // Indexes
        $this->createTable('{{%indexes}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'type' => $this->string(100)->notNull(),
            'service' => $this->string(100)->notNull(),
            'status' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        $this->createIndex('name', '{{%indexes}}', 'name');
        $this->createIndex('type', '{{%type}}', 'type');

        // Index attributes
        $this->createTable('{{%index_attributes}}', [
            'id' => $this->primaryKey(),
            'index_type' => $this->string(100)->notNull(),
            'name' => $this->string(100)->notNull(),
            'index_name' => $this->string(100)->notNull(),
            'type' => $this->string(100)->notNull(),
            'full_text_search' => $this->boolean()->defaultValue(0),
            'boost' => $this->integer()->defaultValue(null)
        ], $tableOptions);

        $this->createIndex('index_type', '{{%index_attributes}}', 'index_type');

        // Facets
        $this->createTable('{{%facets}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'label' => $this->string()->notNull(),
            'entity_type' => $this->string(100)->notNull(),
            'attribute_name' => $this->string(100)->notNull(),
            'index_name' => $this->string(100)->notNull(),
            'from' => $this->string(100)->notNull(),
            'to' => $this->string(100)->notNull(),
            'interval' => $this->string(100)->notNull(),
            'type' => $this->string(100)->notNull(),
            'operator' => $this->string(3)->notNull(),
            'multivalue' => $this->boolean()->defaultValue(0)
        ], $tableOptions);

        $this->addForeignKey('FK_facets_attribute_id', '{{%facets}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');

        // Facet values
        $this->createTable('{{%facet_values}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(100)->notNull(),
            'facet_id' => $this->integer()->notNull(),
            'term' => $this->string()->notNull(),
            'from' => $this->string()->notNull(),
            'to' => $this->string()->notNull(),
            'include_lower_bound' => $this->boolean()->defaultValue(1),
            'include_upper_bound' => $this->boolean()->defaultValue(0),
            'display' => $this->string()->notNull(),
            'sort' => $this->integer()->defaultValue(null)
        ], $tableOptions);

        $this->addForeignKey('FK_facet_values_facet_id', '{{%facet_values}}', 'facet_id', '{{%facets}}', 'id', 'CASCADE', 'CASCADE');

        // Facet ranges
        $this->createTable('{{%facet_ranges}}', [
            'id' => $this->primaryKey(),
            'facet_id' => $this->integer()->notNull(),
            'from' => $this->string()->notNull(),
            'to' => $this->string()->notNull(),
            'include_lower_bound' => $this->boolean()->defaultValue(1),
            'include_upper_bound' => $this->boolean()->defaultValue(0),
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

        // Facet sets
        $this->createTable('{{%facet_sets}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()
        ], $tableOptions);

        // Facet set - Facet junction table
        $this->createTable(
            '{{%facet_set_facets}}',
            [
                'set_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'facet_id' => Schema::TYPE_INTEGER . ' NOT NULL'

            ],
            $tableOptions
        );

        $this->addForeignKey('FK_facet_set_facets_set_id', '{{%facet_set_facets}}', 'set_id', '{{%facet_sets}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_facet_set_facets_facet_id', '{{%facet_set_facets}}', 'facet_id', '{{%facets}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%indexes}}');
        $this->dropTable('{{%index_attributes}}');
        $this->dropTable('{{%facets}}');
        $this->dropTable('{{%facet_ranges}}');
        $this->dropTable('{{%facet_terms}}');
        $this->dropTable('{{%facet_sets}}');
        $this->dropTable('{{%facet_set_facets}}');
    }
}
