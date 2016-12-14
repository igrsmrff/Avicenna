<?php

use yii\db\Migration;

class m161024_114950_Initial_inspections_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('initial_inspections', [
            'id' => $this->primaryKey(),
            'weight' => $this->string()->notNull(),
            'height' => $this->string()->notNull(),
            'blood_pressure' => $this->string()->notNull(),
            'temperature' => $this->string()->notNull(),
            'appointment_id' => $this->integer()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'notes' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('initial_inspections_appointment_id_index','initial_inspections','appointment_id');
        $this->createIndex('initial_inspections_creator_id_index','initial_inspections','creator_id');

        $this->addForeignKey('fk_initial_inspection_to_appointment', 'initial_inspections', 'appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_initial_inspection_to_creator_id', 'initial_inspections', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('initial_inspections');
    }
}
