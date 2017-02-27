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
            'username' => Schema::TYPE_STRING . '(100) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'access_token' => $this->string()->defaultValue(null),
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'role' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "user"',
            'status' => 'tinyint(1) NOT NULL DEFAULT 1',
            'registration_ip' => Schema::TYPE_BIGINT,
            'last_login_ip' => Schema::TYPE_BIGINT,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'confirmed_at' => Schema::TYPE_INTEGER,
            'last_login_at' => Schema::TYPE_INTEGER,
            'blocked_at' => Schema::TYPE_INTEGER
        ], $tableOptions);

        // Indexes
        $this->createIndex('username', '{{%users}}', 'username', true);
        $this->createIndex('email', '{{%users}}', 'email', true);
        $this->createIndex('access_token', '{{%users}}', 'access_token');
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
        $this->addForeignKey('FK_profiles_user_id', '{{%profiles}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        // Users tokens table
        $this->createTable(
            '{{%tokens}}',
            [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER,
                'token' => Schema::TYPE_STRING . '(32) NOT NULL',
                'type' => 'tinyint(1) NOT NULL',
                'created_at' => Schema::TYPE_INTEGER,
                'expire_at' => Schema::TYPE_INTEGER,
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('FK_tokens_user_id', '{{%tokens}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        // Indexes
        $this->createIndex('token', '{{%tokens}}', 'token');
        $this->createIndex('type', '{{%tokens}}', 'type');
        $this->createIndex('expire_at', '{{%tokens}}', 'expire_at');

        // Users tokens table
        $this->createTable(
            '{{%auth}}',
            [
                'id' => Schema::TYPE_PK,
                'user_id' => Schema::TYPE_INTEGER,
                'provider' => Schema::TYPE_STRING . ' NOT NULL',
                'provider_id' => Schema::TYPE_STRING . ' NOT NULL',
                'provider_attributes' => Schema::TYPE_TEXT,
                'created_at' => Schema::TYPE_INTEGER,
                'expire_at' => Schema::TYPE_BOOLEAN,
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('FK_auth_user_id', '{{%auth}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('provider_provider_id', '{{%auth}}', ['provider', 'provider_id']);

        // Add super-administrator
        $this->execute($this->getUserSql());
        $this->execute($this->getProfileSql());
    }

    /**
     * @return string SQL to insert first user
     */
    private function getUserSql()
    {
        $time = time();
        $password_hash = Yii::$app->security->generatePasswordHash('admin12345');
        $auth_key = Yii::$app->security->generateRandomString();
        return "INSERT INTO {{%users}} (`username`, `email`, `password_hash`, `auth_key`, `role`, `status`, `created_at`, `updated_at`) VALUES ('admin', 'admin@demo.com', '$password_hash', '$auth_key', 'superadmin', 1, $time, $time)";
    }

    /**
     * @return string SQL to insert first profile
     */
    private function getProfileSql()
    {
        return "INSERT INTO {{%profiles}} (`user_id`, `first_name`, `last_name`, `avatar_url`) VALUES (1, 'Admin', 'Admin', '')";
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%tokens}}');
        $this->dropTable('{{%profiles}}');
        $this->dropTable('{{%users}}');
    }
}
