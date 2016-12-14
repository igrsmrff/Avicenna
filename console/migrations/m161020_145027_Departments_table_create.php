<?php

use yii\db\Migration;

class m161020_145027_Departments_table_create extends \console\overrides\db\Migration
{
    public function up()
    {

        $this->createTable('departments', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->string(),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer(),
        ],$this->tableOptions);

        $this->insert('departments', [
            'title'=>'default department',
            'description' =>'default description',
        ]);
    }


    public function down()
    {
        $this->dropTable('departments');
    }

}
