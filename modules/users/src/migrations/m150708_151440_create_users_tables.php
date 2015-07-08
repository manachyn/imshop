<?php

use yii\db\Schema;
use yii\db\Migration;

class m150708_151440_create_users_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Users table
        $this->createTable('{{%users}}', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "user"',

            'status' => 'tinyint(1) NOT NULL DEFAULT 1',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ], $tableOptions);

        // Indexes
        $this->createIndex('username', '{{%users}}', 'username', true);
        $this->createIndex('email', '{{%users}}', 'email', true);
        $this->createIndex('role', '{{%users}}', 'role');
        $this->createIndex('status', '{{%users}}', 'status');

        // Users profiles table
        $this->createTable(
            '{{%profiles}}',
            [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER,
                'first_name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'last_name' => Schema::TYPE_STRING . '(100) NOT NULL',
                'avatar_url' => Schema::TYPE_STRING . '(100) NOT NULL'
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('FK_profile_user', '{{%profiles}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        // Add super-administrator
        $this->execute($this->getUserSql());
        $this->execute($this->getProfileSql());


//        // Users table
//        $this->createTable(
//            '{{%users}}',
//            [
//                'id' => Schema::TYPE_PK,
//                'username' => Schema::TYPE_STRING . '(30) NOT NULL',
//                'email' => Schema::TYPE_STRING . '(100) NOT NULL',
//                'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
//                'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
//                'token' => Schema::TYPE_STRING . '(53) NOT NULL',
//                'role' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT "user"',
//                'status_id' => 'tinyint(4) NOT NULL DEFAULT 0',
//                'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
//                'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL'
//            ],
//            $tableOptions
//        );
//
//        // Indexes
//        $this->createIndex('username', '{{%users}}', 'username', true);
//        $this->createIndex('email', '{{%users}}', 'email', true);
//        $this->createIndex('role', '{{%users}}', 'role');
//        $this->createIndex('status_id', '{{%users}}', 'status_id');
//        $this->createIndex('created_at', '{{%users}}', 'created_at');
    }

    /**
     * @return string SQL to insert first user
     */
    private function getUserSql()
    {
        $time = time();
        $password_hash = Yii::$app->security->generatePasswordHash('admin12345');
        $auth_key = Yii::$app->security->generateRandomString();
        $token = Yii::$app->security->generateRandomString() . '_' . time();
        return "INSERT INTO {{%users}} (`username`, `email`, `password_hash`, `auth_key`, `password_reset_token`, `role`, `status`, `created_at`, `updated_at`) VALUES ('admin', 'admin@demo.com', '$password_hash', '$auth_key', '$token', 'superadmin', 1, $time, $time)";
    }

    /**
     * @return string SQL to insert first profile
     */
    private function getProfileSql()
    {
        return "INSERT INTO {{%profiles}} (`user_id`, `first_name`, `last_name`, `avatar_url`) VALUES (1, 'Administration', 'Site', '')";
    }

    public function safeDown()
    {
        $this->dropTable('{{%profiles}}');
        $this->dropTable('{{%users}}');
    }
}
