<?php

use yii\db\Migration;

class m161024_114727_Notes_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('notes', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'text' => $this->text(),
            'user_id' => $this->integer(),
            'color' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('notes_user_id_index','notes','user_id');
        $this->addForeignKey('fk_note_to_user', 'notes', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('notes');
    }
}
