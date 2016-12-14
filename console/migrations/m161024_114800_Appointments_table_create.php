<?php

use yii\db\Migration;

class m161024_114800_Appointments_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('appointments', [
            'id' => $this->primaryKey(),
            'date' => $this->string()->notNull(),
            'time' => $this->string()->notNull(),
            'status' => $this->string(),
            'creator_id' => $this->integer(),
            'patient_id' => $this->integer(),
            'doctor_id' => $this->integer(),
            'nurse_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('appointments_creator_id_index','appointments','creator_id');
        $this->createIndex('appointments_patient_id_index','appointments','patient_id');
        $this->createIndex('appointments_doctor_id_index','appointments','doctor_id');
        $this->createIndex('appointments_nurse_id_index','appointments','nurse_id');

        $this->addForeignKey('fk_appointments_creator_id', 'appointments', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_appointments_patient_id', 'appointments', 'patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_appointments_doctor_id', 'appointments', 'doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_appointments_nurse_id', 'appointments', 'nurse_id', 'nurses', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('appointments');
    }
}
