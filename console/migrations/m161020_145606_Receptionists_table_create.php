<?php

use yii\db\Migration;

class m161020_145606_Receptionists_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('receptionists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'receptionistImageUrl' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('receptionist_user_id_index','receptionists','user_id');
        $this->addForeignKey('fk_receptionist_to_user', 'receptionists', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('receptionists');
    }
}
