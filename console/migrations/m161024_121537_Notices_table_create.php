<?php

use yii\db\Migration;

class m161024_121537_Notices_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('notices', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('notices');
    }
}
