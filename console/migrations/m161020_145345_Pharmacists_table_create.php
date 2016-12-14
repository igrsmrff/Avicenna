<?php

use yii\db\Migration;

class m161020_145345_Pharmacists_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('pharmacists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'pharmacistImageUrl' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('pharmacists_user_id_index','pharmacists','user_id');
        $this->addForeignKey('fk_pharmacist_to_user', 'pharmacists', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('pharmacists');
    }
}
