<?php

use yii\db\Migration;

class m161020_145156_Patients_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('patients', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->defaultValue(''),
            'address' => $this->string()->defaultValue(''),
            'phone' => $this->string()->defaultValue(''),
            'birth_date' => $this->string()->defaultValue(''),

            'insurance_expiration_date' => $this->string()->defaultValue(''),
            'insurance_company_id' => $this->integer()->notNull(),
            'insurance_number' => $this->string()->notNull(),
            'sex' => $this->integer()->notNull(),
            'marital_status' => $this->integer()->notNull(),

            'patientImageUrl' => $this->string()->defaultValue(''),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('patients_insurance_company_id_index','patients','insurance_company_id');
        $this->addForeignKey('fk_patient_to_insurance_company', 'patients', 'insurance_company_id', 'insurance_companies', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('patients');
    }

}
