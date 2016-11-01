<?php

use yii\db\Migration;

class m161029_184723_add_form_request_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema('{{%form_requests}}', true)) {
            $this->addColumn('{{%form_requests}}', 'name', $this->string()->defaultValue(null));
            $this->addColumn('{{%form_requests}}', 'email', $this->string()->defaultValue(null));
            $this->addColumn('{{%form_requests}}', 'phone', $this->string()->defaultValue(null));
            $this->addColumn('{{%form_requests}}', 'text', $this->text()->defaultValue(null));
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if ($this->db->schema->getTableSchema('{{%form_requests}}', true)) {
            $this->dropColumn('{{%form_requests}}', 'name');
            $this->dropColumn('{{%form_requests}}', 'email');
            $this->dropColumn('{{%form_requests}}', 'phone');
            $this->dropColumn('{{%form_requests}}', 'text');
        }
    }
}
