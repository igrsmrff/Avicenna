<?php

use yii\db\Migration;

class m161024_114850_Invoices_table_create extends \console\overrides\db\Migration
{
    public function up()
    {

        $this->createTable('invoices', [
            'id' => $this->primaryKey(),
            'invoice_number' => $this->string(),
            'due_date' => $this->string(),
            'status' => $this->integer(),
            'vat_percentage' => $this->string(),
            'discount_amount' => $this->string(),
            'discount_amount_percent' => $this->string(),
            'sub_total_amount' => $this->string(),
            'total_invoice_amount' => $this->string(),
            'appointment_id' => $this->integer(),
            'creator_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('invoices_appointment_id_index','invoices','appointment_id');
        $this->createIndex('invoices_creator_id_index','invoices','creator_id');

        $this->addForeignKey('fk_invoice_to_appointment', 'invoices', 'appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_invoice_to_creator', 'invoices', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('invoices');
    }
}
