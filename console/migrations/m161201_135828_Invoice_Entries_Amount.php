<?php

use yii\db\Migration;

class m161201_135828_Invoice_Entries_Amount extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('invoice_entries_amount', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer(),
            'entry_id' => $this->integer(),
            'amount' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('invoice_entries_amount_invoices_id_index','invoice_entries_amount','invoice_id');
        $this->createIndex('invoice_entries_amount_entry_id_index','invoice_entries_amount','entry_id');
        $this->addForeignKey(
            'fk_invoice_entries_amount_to_invoice',
            'invoice_entries_amount', 'invoice_id',
            'invoices', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey(
            'fk_invoice_entries_amount_to_invoice_entry_drop_down',
            'invoice_entries_amount', 'entry_id',
            'invoice_entry_drop_down', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('invoice_entries_amount');
    }
}
