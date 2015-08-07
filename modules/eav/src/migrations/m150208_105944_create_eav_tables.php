<?php

use yii\db\Schema;
use yii\db\Migration;

class m150208_105944_create_eav_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Attributes
        $this->createTable(
            '{{%eav_attributes}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'presentation' => Schema::TYPE_STRING . ' NOT NULL',
                'type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'field_config_data' => Schema::TYPE_TEXT . ' NOT NULL',
                'rules_config_data' => Schema::TYPE_TEXT . ' NOT NULL',
                //'form_field_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            ],
            $tableOptions
        );

        // Values
        $this->createTable(
            '{{%aev_values}}',
            [
                'id' => Schema::TYPE_PK,
                'attribute_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'value' => Schema::TYPE_STRING . '(255) NOT NULL',
                'presentation' => Schema::TYPE_STRING . '(255) NOT NULL'
            ],
            $tableOptions
        );

        //$this->addForeignKey('FK_attributes_form_field_id', '{{%attributes}}', 'form_field_id', '{{%form_fields}}', 'id', 'SET NULL', 'CASCADE');

        // Attribute values
        $this->createTable(
            '{{%eav_entity_values}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'entity_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'attribute_name' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'attribute_type' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'value_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'string_value' => Schema::TYPE_STRING . '(255) NOT NULL',
                'value_entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%eav_entity_values}}', 'entity_id');
        $this->createIndex('entity_type', '{{%eav_entity_values}}', 'entity_type');
        $this->addForeignKey('FK_eav_entity_values_attribute_id', '{{%eav_entity_values}}', 'attribute_id', '{{%eav_attributes}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('attribute_name', '{{%eav_entity_values}}', 'attribute_name');
        $this->addForeignKey('FK_eav_entity_values_value_id', '{{%eav_product_values}}', 'value_id', '{{%eav_values}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('string_value', '{{%eav_entity_values}}', 'string_value');
        $this->createIndex('value_entity_id', '{{%eav_entity_values}}', 'value_entity_id');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%eav_entity_values}}');
        $this->dropTable('{{%eav_attributes}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
