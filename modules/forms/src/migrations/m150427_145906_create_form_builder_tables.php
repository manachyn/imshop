<?php

use yii\db\Migration;

class m150427_145906_create_form_builder_tables extends Migration
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

        // Forms
        $this->createTable(
            '{{%forms}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(100)->defaultValue(null),
            ],
            $tableOptions
        );

//        // Fields
//        $this->createTable(
//            '{{%form_fields}}',
//            [
//                'id' =>$this->primaryKey(),
//                'form_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
//                'name' => Schema::TYPE_STRING . '(100) NOT NULL',
//                'label' => Schema::TYPE_STRING . '(100) NOT NULL',
//                'hint' => Schema::TYPE_STRING . '(100) NOT NULL',
//                'type' => Schema::TYPE_STRING . '(100) NOT NULL',
//                'options_data' =>  Schema::TYPE_TEXT,
//                'rules_data' =>  Schema::TYPE_TEXT,
//                'items' =>  Schema::TYPE_STRING . '(255) NOT NULL'
//            ],
//            $tableOptions
//        );

//        $this->addForeignKey('FK_form_fields_form_id', '{{%form_fields}}', 'form_id', '{{%forms}}', 'id', 'CASCADE', 'CASCADE');

        // Requests
        $this->createTable(
            '{{%form_requests}}',
            [
                'id' => $this->primaryKey(),
                'request_type' => $this->string(100)->notNull(),
                'form_id' => $this->integer()->defaultValue(null),
                'form_name' => $this->string(100)->defaultValue(null),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        $this->addForeignKey('FK_form_requests_form_id', '{{%form_requests}}', 'form_id', '{{%forms}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->dropTable('{{%forms}}');
//        $this->dropTable('{{%form_fields}}');
        $this->dropTable('{{%form_requests}}');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
