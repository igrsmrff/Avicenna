<?php

use yii\db\Migration;

class m161201_135900_Sms_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('sms', [
            'id' => $this->primaryKey(),
            'text_message' => $this->text(),
            'status' => $this->integer(),
            'appointment_id' => $this->integer(),
            'creator_id' => $this->integer(),
            'message_identifier' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('sms_appointment_id_index','sms','appointment_id');
        $this->createIndex('sms_creator_id_index','sms','creator_id');
        $this->addForeignKey('fk_sms_appointment_id', 'sms', 'appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_sms_creator_id', 'sms', 'creator_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('sms');
    }
}
