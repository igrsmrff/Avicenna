<?php

use yii\db\Migration;

class m161020_145240_Nurses_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('nurses', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->defaultValue(''),
            'address' => $this->string()->defaultValue(''),
            'phone' => $this->string()->defaultValue(''),
            'nurseImageUrl' => $this->string()->defaultValue(''),
            'department_id' =>$this->integer()->defaultValue(1),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->tableOptions);

        $this->createIndex('nurses_index','nurses','department_id');
        $this->createIndex('nurses_user_id_index','nurses','user_id');
        $this->addForeignKey('fk_nurse_to_department', 'nurses', 'department_id', 'departments', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_nurse_to_user', 'nurses', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('nurses');
    }
}
