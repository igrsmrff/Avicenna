<?php

use yii\db\Migration;

class m161024_114890_Payments_table_create extends \console\overrides\db\Migration
{
    public function up()
    {

        $this->createTable('payments', [
            'id' => $this->primaryKey(),
            'payment_method' => $this->integer(),
            'invoice_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('invoices_index','payments','invoice_id');
        $this->addForeignKey('fk_payment_to_invoice', 'payments', 'invoice_id', 'invoices', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('payments');
    }
}
