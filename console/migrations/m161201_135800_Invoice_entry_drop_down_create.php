<?php

use yii\db\Migration;

class m161201_135800_Invoice_entry_drop_down_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('invoice_entry_drop_down', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

    }

    public function down()
    {
        $this->dropTable('invoice_entry_drop_down');
    }
}