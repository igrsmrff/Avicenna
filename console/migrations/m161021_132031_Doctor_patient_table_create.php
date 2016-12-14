<?php

use yii\db\Migration;

class m161021_132031_Doctor_patient_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('doctor_patient', [
            'id' => $this->primaryKey(),
            'doctor_id' => $this->integer(),
            'patient_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('doctor_patient_doctor_id_index','doctor_patient','doctor_id');
        $this->createIndex('doctor_patient_patient_id_index','doctor_patient','patient_id');

        $this->addForeignKey('fk_doctor_patient_doctor_id', 'doctor_patient', 'doctor_id', 'doctors', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_doctor_patient_patient_id', 'doctor_patient', 'patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('doctor_patient');
    }
}
