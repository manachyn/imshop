<?php

use yii\db\Schema;
use yii\db\Migration;

class m150208_114635_create_variation_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Options
        $this->createTable(
            '{{%options}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'presentation' => Schema::TYPE_STRING . ' NOT NULL'
            ],
            $tableOptions
        );

        // Option values
        $this->createTable(
            '{{%option_values}}',
            [
                'id' => Schema::TYPE_PK,
                'option_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'value' => Schema::TYPE_STRING . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->addForeignKey('FK_option_values_option_id', '{{%option_values}}', 'option_id', '{{%options}}', 'id', 'CASCADE', 'CASCADE');

        // Variants
        $this->createTable(
            '{{%variants}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'entity_type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'presentation' => Schema::TYPE_STRING . ' NOT NULL',
                'master' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('entity_id', '{{%variants}}', 'entity_id');
        $this->createIndex('entity_type', '{{%variants}}', 'entity_type');

        // Variant - Option value junction table
        $this->createTable(
            '{{%variant_option_values}}',
            [
                'variant_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'option_value_id' => Schema::TYPE_INTEGER . ' NOT NULL'

            ],
            $tableOptions
        );
        //$this->addPrimaryKey('PK_variant_option_values', '{{%variant_option_values}}', 'variant_id, option_value_id');
        $this->addForeignKey('FK_variant_option_values_variant_id', '{{%variant_option_values}}', 'variant_id', '{{%variants}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('FK_variant_option_values_option_value_id', '{{%variant_option_values}}', 'option_value_id', '{{%option_values}}', 'id', 'CASCADE', 'CASCADE');
    }
    
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%variant_option_values}}');
        $this->dropTable('{{%variants}}');
        $this->dropTable('{{%option_values}}');
        $this->dropTable('{{%options}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
