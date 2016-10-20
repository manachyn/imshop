<?php

use yii\db\Migration;

/**
 * Handles the creation for table `banners_tables`.
 */
class m160904_132447_create_banners_tables extends Migration
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

        // Banners
        $this->createTable(
            '{{%banners}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->boolean()->defaultValue(1),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Banner items
        $this->createTable(
            '{{%banner_items}}',
            [
                'id' => $this->primaryKey(),
                'banner_id' => $this->integer()->notNull(),
                'filesystem' => $this->string(100)->notNull(),
                'path' => $this->string()->notNull(),
                'title' => $this->string()->defaultValue(null),
                'caption' => $this->string()->defaultValue(null),
                'link' => $this->string()->defaultValue(null),
                'size' =>  $this->integer()->notNull(),
                'mime_type' => $this->string()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'sort' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        $this->addForeignKey('FK_banner_items_banner_id', '{{%banner_items}}', 'banner_id', '{{%banners}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('sort', '{{%banner_items}}', 'sort');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%banner_items}}');
        $this->dropTable('{{%banners}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
