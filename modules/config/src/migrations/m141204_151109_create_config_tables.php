<?php

use yii\db\Migration;

class m141204_151109_create_config_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable(
            '{{%config}}',
            [
                'id' => $this->primaryKey(),
                'key' => $this->string(100)->notNull(),
                'value' => $this->text(),
                'context' => $this->string(100)->defaultValue(null)
            ],
            $tableOptions
        );
        $this->createIndex('key', '{{%config}}', 'key');
        $this->createIndex('context', '{{%config}}', 'context');
    }

    public function down()
    {
        $this->dropTable('{{%config}}');
    }
}

