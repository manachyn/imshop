<?php

use yii\db\Migration;

/**
 * Handles the creation for table `galleries_tables`.
 */
class m160701_153553_create_galleries_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Galleries
        $this->createTable(
            '{{%galleries}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->boolean()->defaultValue(1),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Gallery items
        $this->createTable(
            '{{%gallery_items}}',
            [
                'id' => $this->primaryKey(),
                'gallery_id' => $this->integer()->notNull(),
                'filesystem' => $this->string(100)->notNull(),
                'path' => $this->string()->notNull(),
                'title' => $this->string()->defaultValue(null),
                'caption' => $this->string()->defaultValue(null),
                'alt_text' => $this->string()->defaultValue(null),
                'size' =>  $this->integer()->notNull(),
                'mime_type' => $this->string()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'sort' => $this->integer()->notNull(),
                'status' => $this->boolean()->defaultValue(1),
            ],
            $tableOptions
        );

        $this->addForeignKey('FK_gallery_items_gallery_id', '{{%gallery_items}}', 'gallery_id', '{{%galleries}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('sort', '{{%gallery_items}}', 'sort');

        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->addColumn('{{%widgets}}', 'display_title', $this->boolean()->defaultValue(1));
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%gallery_items}}');
        $this->dropTable('{{%galleries}}');
        if ($this->db->schema->getTableSchema('{{%widgets}}', true)) {
            $this->dropColumn('{{%widgets}}', 'display_title');
        }
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
