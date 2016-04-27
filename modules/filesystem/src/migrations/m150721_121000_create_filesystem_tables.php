<?php

use yii\db\Schema;
use yii\db\Migration;

class m150721_121000_create_filesystem_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Files
        $this->createTable(
            '{{%files}}',
            [
                'id' => Schema::TYPE_PK,
                'filesystem' => Schema::TYPE_STRING . '(100) NOT NULL',
                'path' => Schema::TYPE_STRING . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'size' => Schema::TYPE_INTEGER,
                'mime_type' => Schema::TYPE_STRING . ' NOT NULL',
                'created_at' => Schema::TYPE_INTEGER,
                'updated_at' => Schema::TYPE_INTEGER,
            ],
            $tableOptions
        );

        $this->createIndex('filesystem', '{{%files}}', 'filesystem');
        $this->createIndex('path', '{{%files}}', 'path');
        $this->createIndex('created_at', '{{%files}}', 'created_at');
        $this->createIndex('updated_at', '{{%files}}', 'updated_at');

        $this->createTable(
            '{{%entity_files}}',
            [
                'id' => Schema::TYPE_PK,
                'entity_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'entity_type' => Schema::TYPE_STRING . ' NOT NULL',
                'attribute' => Schema::TYPE_STRING . '(100) NOT NULL',
                'file_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            $tableOptions
        );

        $this->createIndex('entity_id_entity_type', '{{%entity_files}}', ['entity_id', 'entity_type']);
        $this->createIndex('attribute', '{{%entity_files}}', 'attribute');
        $this->addForeignKey('FK_entity_files_file_id', '{{%entity_files}}', 'file_id', '{{%files}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%entity_files}}');
        $this->dropTable('{{%files}}');
    }
}
