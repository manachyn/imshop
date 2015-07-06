<?php

use yii\db\Schema;
use yii\db\Migration;

class m141204_151109_create_module_tables extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        // Config
        $this->createTable(
            '{{%config}}',
            [
                'id' => Schema::TYPE_PK,
                'component' => Schema::TYPE_STRING . '(50) NOT NULL',
                'key' => Schema::TYPE_STRING . '(64) NOT NULL',
                'value' => Schema::TYPE_TEXT . ' NOT NULL'
            ],
            $tableOptions
        );
        $this->createIndex('component', '{{%config}}', 'component');
        $this->createIndex('key', '{{%config}}', 'key');
    }

    public function down()
    {
        $this->dropTable('{{%config}}');
    }
}
