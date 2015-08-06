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
            'entity_type' => $this->string()->notNull(),
            'server' => $this->string(100)->notNull(),
            'status' => $this->boolean()->defaultValue(1)
        ], $tableOptions);

        // Index attributes
        $this->createTable('{{%index_attributes}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'type' => $this->string(100)->notNull(),
            'entity_type' => $this->string()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%indexes}}');
        $this->dropTable('{{%index_attributes}}');
    }
}
