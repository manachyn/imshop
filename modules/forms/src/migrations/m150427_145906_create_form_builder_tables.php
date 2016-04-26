<?php

use yii\db\Schema;
use yii\db\Migration;

class m150427_145906_create_form_builder_tables extends Migration
{
    public function up()
    {

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Forms
        $this->createTable(
            '{{%forms}}',
            [
                'id' => Schema::TYPE_PK,
                'name' => Schema::TYPE_STRING . '(100) NOT NULL'
            ],
            $tableOptions
        );

        // Fields
        $this->createTable(
            '{{%form_fields}}',
            [
                'id' => Schema::TYPE_PK,
                'form_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'label' => Schema::TYPE_STRING . '(100) NOT NULL',
                'hint' => Schema::TYPE_STRING . '(100) NOT NULL',
                'type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'options_data' =>  Schema::TYPE_TEXT,
                'rules_data' =>  Schema::TYPE_TEXT,
                'items' =>  Schema::TYPE_STRING . '(255) NOT NULL'
            ],
            $tableOptions
        );

        $this->addForeignKey('FK_form_fields_form_id', '{{%form_fields}}', 'form_id', '{{%forms}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%forms}}');
        $this->dropTable('{{%form_fields}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
