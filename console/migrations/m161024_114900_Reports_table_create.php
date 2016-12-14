<?php

use yii\db\Migration;

class m161024_114900_Reports_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('reports', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'creator_id' => $this->integer(),
            'appointment_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('reports_creator_id_index','reports','creator_id');
        $this->createIndex('reports_appointment_id_index','reports','appointment_id');

        $this->addForeignKey('fk_report_to_creator', 'reports', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_report_appointment_id', 'reports', 'appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('reports');
    }
}
