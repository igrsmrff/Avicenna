<?php

use yii\db\Migration;
class m161021_132060_Nurse_patient_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('nurse_patient', [
            'id' => $this->primaryKey(),
            'nurse_id' => $this->integer(),
            'patient_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('nurse_patient_nurse_id_index','nurse_patient','nurse_id');
        $this->createIndex('nurse_patient_patient_id_index','nurse_patient','patient_id');

        $this->addForeignKey('fk_nurse_patient_nurse_id', 'nurse_patient', 'nurse_id', 'nurses', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_nurse_patient_patient_id', 'nurse_patient', 'patient_id', 'patients', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('nurse_patient');
    }
}
