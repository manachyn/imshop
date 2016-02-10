<?php

use yii\db\Schema;
use yii\db\Migration;

class m150616_154032_create_tasks_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        // Tasks
        $this->createTable(
            '{{%tasks}}',
            [
                'id' => Schema::TYPE_PK,
                'type' => Schema::TYPE_STRING . '(100) NOT NULL',
                'name' => Schema::TYPE_STRING . '(255) NOT NULL',
                'description' => Schema::TYPE_STRING . '(255) NOT NULL',
                'status' => 'tinyint(1) NOT NULL DEFAULT 0',
                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%categories}}');
    }
}
