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
            '{{%attributes}}',
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

        //$this->addForeignKey('FK_attributes_form_field_id', '{{%attributes}}', 'form_field_id', '{{%form_fields}}', 'id', 'SET NULL', 'CASCADE');

        // Attribute values
        $this->createTable(
            '{{%attribute_values}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'entity_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'attribute_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'attribute_name' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'attribute_type' => Schema::TYPE_STRING . '(100) NOT NULL', // Denormalization for performance
                'string_value' => Schema::TYPE_STRING . '(255) NOT NULL',
                'integer_value' => Schema::TYPE_INTEGER . ' DEFAULT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%attribute_values}}', 'entity_id');
        $this->createIndex('entity_type', '{{%attribute_values}}', 'entity_type');
        $this->addForeignKey('FK_attribute_values_attribute_id', '{{%attribute_values}}', 'attribute_id', '{{%attributes}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('attribute_name', '{{%attribute_values}}', 'attribute_name');
        $this->createIndex('string_value', '{{%attribute_values}}', 'string_value');
        $this->createIndex('integer_value', '{{%attribute_values}}', 'integer_value');
    }

    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%attribute_values}}');
        $this->dropTable('{{%attributes}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
