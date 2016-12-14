<?php

use yii\db\Migration;

class m161201_136000_Users_schedule_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('users_schedule', [
            'id' => $this->primaryKey(),
            'date' => $this->string(),
            'start_time' => $this->string(),
            'end_time' => $this->string(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('users_schedule_user_id_index','users_schedule','user_id');
        $this->addForeignKey(
            'fk_users_schedule_to_user',
            'users_schedule', 'user_id',
            'user', 'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('users_schedule');
    }
}