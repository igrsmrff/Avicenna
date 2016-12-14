<?php

use yii\db\Migration;

class m161024_082920_Messages_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('messages', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer(),
            'receiver_id' => $this->integer(),
            'text_message' => $this->text(),
            'status' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('messages_sender_id','messages','sender_id');
        $this->createIndex('messages_receiver_id','messages','receiver_id');

        $this->addForeignKey('fk_messages_to_sender', 'messages', 'sender_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_messages_to_receiver_id', 'messages', 'receiver_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('messages');
    }
}
