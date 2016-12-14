<?php

use yii\db\Migration;

class m161020_145706_Accountants_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('accountants', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'accountantImageUrl' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('receptionist_user_id_index','accountants','user_id');
        $this->addForeignKey('fk_accountants_to_user', 'accountants', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('accountants');
    }
}
