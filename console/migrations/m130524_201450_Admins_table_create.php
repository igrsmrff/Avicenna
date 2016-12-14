<?php

use yii\db\Migration;

class m130524_201450_Admins_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('admins', [
            'id' => $this->primaryKey(),
            'system_name' => $this->string()->notNull(),
            'system_title' => $this->string()->notNull(),
            'name' => $this->string(),
            'address' => $this->string(),
            'paypal_email' => $this->string(),
            'currency' => $this->string(),
            'phone' => $this->string(),
            'phone_country_code' => $this->string(),
            'system_email' => $this->text(),
            'vat_percentage' => $this->string(),
            'adminImageUrl' => $this->string(),
            'user_id' =>$this->integer()->notNull(),
            'language' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('admins_user_id_index','admins','user_id');
        $this->addForeignKey('fk_admin_to_user', 'admins', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('admins');
    }
}
