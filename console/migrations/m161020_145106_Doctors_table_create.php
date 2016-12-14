<?php

use yii\db\Migration;

class m161020_145106_Doctors_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('doctors', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->defaultValue(''),
            'address' => $this->string()->defaultValue(''),
            'phone' => $this->string()->defaultValue(''),
            'profile' => $this->text(),
            'doctorImageUrl' => $this->string()->defaultValue(''),
            'department_id' =>$this->integer()->notNull()->defaultValue(1),
            'user_id' =>$this->integer()->notNull(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ], $this->tableOptions);

        $this->createIndex('doctors_department_id_index','doctors','department_id');
        $this->createIndex('doctors_user_id_index','doctors','user_id');
        $this->addForeignKey('fk_doctor_to_department', 'doctors', 'department_id', 'departments', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_doctor_to_user', 'doctors', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('doctors');
    }
}
