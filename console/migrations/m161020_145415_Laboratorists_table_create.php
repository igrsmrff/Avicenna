<?php

use yii\db\Migration;

class m161020_145415_Laboratorists_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('laboratorists', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'address' => $this->string(),
            'phone' => $this->string(),
            'laboratoristImageUrl' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('laboratorists_user_id_index','pharmacists','user_id');
        $this->addForeignKey('fk_laboratorist_to_user', 'laboratorists', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('laboratorists');
    }
}
