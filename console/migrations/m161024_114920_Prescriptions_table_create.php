<?php

use yii\db\Migration;

class m161024_114920_Prescriptions_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('prescriptions', [
            'id' => $this->primaryKey(),
            'medication' => $this->text(),
            'note' => $this->text(),
            'creator_id' => $this->integer(),
            'report_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('prescriptions_creator_id_index','prescriptions','creator_id');
        $this->createIndex('prescriptions_report_id_index','prescriptions','report_id');

        $this->addForeignKey('fk_prescriptions_to_creator', 'prescriptions', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_prescriptions_to_report', 'prescriptions', 'report_id', 'reports', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('prescriptions');
    }
}
