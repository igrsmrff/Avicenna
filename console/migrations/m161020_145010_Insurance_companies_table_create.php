<?php

use yii\db\Migration;

class m161020_145010_Insurance_companies_table_create extends \console\overrides\db\Migration
{
    public function up()
    {
        $this->createTable('insurance_companies', [
            'id' => $this->primaryKey(),
            'company_title' => $this->string()->defaultValue(''),
            'company_description' => $this->string()->defaultValue(''),
            'companyImageUrl' => $this->string()->defaultValue(''),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('insurance_companies');
    }

}
