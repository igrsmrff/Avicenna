<?php

use console\models\User;
use yii\db\Migration;

class m161201_139000_dev_admin extends \console\overrides\db\Migration
{
    protected $tableName = '{{%user}}';

    public function up()
    {
        $this->insert('invoice_entry_drop_down', [
            'title' => 'General Checkup',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('invoice_entry_drop_down', [
            'title' => 'Pills',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('invoice_entry_drop_down', [
            'title' => 'Invoice Entry',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('insurance_companies', [
            'company_title' => 'company 1',
            'company_description' => 'description 1',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('insurance_companies', [
            'company_title' => 'company 2',
            'company_description' => 'description 2',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('insurance_companies', [
            'company_title' => 'company 3',
            'company_description' => 'description 3',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('insurance_companies', [
            'company_title' => 'company 4',
            'company_description' => 'description 4',
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('insurance_companies', [
            'company_title' => 'company 5',
            'company_description' => 'description 5',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //admin insert
        $this->insert($this->tableName, [
            'username' => 'admin',
            'email' => 'developer@wonderslab.com',
            'role' => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('admins', [
            'system_name' => 'Jeddah, Saudi Arabia',
            'system_title' => 'Avicenna',
            'name' => 'admin',
            'address' => 'address',
            'paypal_email' => 'paypal@email.com',
            'currency' => 'currency',
            'phone' => '6504625266',
            'phone_country_code' => '+96',
            'vat_percentage' => '20',
            'system_email' => 'system@email.com',
            'adminImageUrl' => 'adminImageUrl',
            'user_id' => 1,
            'language' => 'language',
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //doctor1 insert
        $this->insert($this->tableName, [
            'username' => 'doctor1',
            'email' => 'doctor1@doctor1.com',
            'role' => User::ROLE_DOCTOR,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('doctor1'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('doctors', [
            'name' => 'doctor1',
            'address' => 'doctor1 address',
            'phone' => '123456789',
            'profile' => 'doctor1 profile',
            'department_id' => 1,
            'user_id' => 2,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //doctor2 insert
        $this->insert($this->tableName, [
            'username' => 'doctor2',
            'email' => 'doctor2@doctor2.com',
            'role' => User::ROLE_DOCTOR,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('doctor2'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('doctors', [
            'name' => 'doctor2',
            'address' => 'doctor2 address',
            'phone' => '123456789',
            'profile' => 'doctor2 profile',
            'department_id' => 1,
            'user_id' => 3,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //doctor3 insert
        $this->insert($this->tableName, [
            'username' => 'doctor3',
            'email' => 'doctor3@doctor3.com',
            'role' => User::ROLE_DOCTOR,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('doctor3'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('doctors', [
            'name' => 'doctor3',
            'address' => 'doctor3 address',
            'phone' => '123456789',
            'profile' => 'doctor3 profile',
            'department_id' => 1,
            'user_id' => 4,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //nurse1 insert
        $this->insert($this->tableName, [
            'username' => 'nurse1',
            'email' => 'nurse1@nurse1.com',
            'role' => User::ROLE_NURSE,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('nurse1'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('nurses', [
            'name' => 'nurse1',
            'address' => 'nurse1 address',
            'phone' => '123456789',
            'department_id' => 1,
            'user_id' => 5,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //nurse2 insert
        $this->insert($this->tableName, [
            'username' => 'nurse2',
            'email' => 'nurse2@nurse2.com',
            'role' => User::ROLE_NURSE,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('nurse2'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('nurses', [
            'name' => 'nurse2',
            'address' => 'nurse2 address',
            'phone' => '123456789',
            'department_id' => 1,
            'user_id' => 6,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //nurse3 insert
        $this->insert($this->tableName, [
            'username' => 'nurse3',
            'email' => 'nurse3@nurse3.com',
            'role' => User::ROLE_NURSE,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('nurse3'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('nurses', [
            'name' => 'nurse3',
            'address' => 'nurse3 address',
            'phone' => '123456789',
            'department_id' => 1,
            'user_id' => 7,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //receptionists
        $this->insert($this->tableName, [
            'username' => 'receptionist1',
            'email' => 'receptionist1@avicenna.me',
            'role' => User::ROLE_RECEPTIONIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('receptionist1'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('receptionists', [
            'name' => 'receptionist1',
            'address' => 'receptionist1 address',
            'phone' => '123456789',
            'user_id' => 8,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'username' => 'receptionist2',
            'email' => 'receptionist2@avicenna.me',
            'role' => User::ROLE_RECEPTIONIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('receptionist2'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('receptionists', [
            'name' => 'receptionist2',
            'address' => 'receptionist2 address',
            'phone' => '123456789',
            'user_id' => 9,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //pharmacists
        $this->insert($this->tableName, [
            'username' => 'pharmacists1',
            'email' => 'pharmacists1@avicenna.me',
            'role' => User::ROLE_PHARMACIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('pharmacists1'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('pharmacists', [
            'name' => 'pharmacists1',
            'address' => 'pharmacists1 address',
            'phone' => '123456789',
            'user_id' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert($this->tableName, [
            'username' => 'pharmacists2',
            'email' => 'pharmacists2@avicenna.me',
            'role' => User::ROLE_PHARMACIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('pharmacists2'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('pharmacists', [
            'name' => 'pharmacists2',
            'address' => 'pharmacists2 address',
            'phone' => '123456789',
            'user_id' => 11,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'username' => 'laboratorist1',
            'email' => 'laboratorist1@avicenna.me',
            'role' => User::ROLE_LABORATORIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('laboratorist1'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('laboratorists', [
            'name' => 'laboratorist1',
            'address' => 'laboratorist1 address',
            'phone' => '123456789',
            'user_id' => 12,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert($this->tableName, [
            'username' => 'laboratorist2',
            'email' => 'laboratorist2@avicenna.me',
            'role' => User::ROLE_LABORATORIST,
            'status' => User::STATUS_ACTIVE,
            'password_hash' => Yii::$app->security->generatePasswordHash('laboratorist2'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('laboratorists', [
            'name' => 'laboratorist2',
            'address' => 'laboratorist2 address',
            'phone' => '123456789',
            'user_id' => 13,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        //insert patients
        $this->insert('patients', [
            'name' => 'patient1',
            'address' => 'patient1 address',
            'phone' => '123456789',
            'birth_date' => '2001-01-01',
            'insurance_expiration_date' => '2001-01-01',
            'insurance_company_id' => 1,
            'insurance_number' => '11111111',
            'sex' => 1,
            'marital_status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('patients', [
            'name' => 'patient2',
            'address' => 'patient2 address',
            'phone' => '123456789',
            'birth_date' => '2002-02-02',
            'insurance_expiration_date' => '2002-02-02',
            'insurance_company_id' => 2,
            'insurance_number' => '22222222',
            'sex' => 2,
            'marital_status' => 2,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('patients', [
            'name' => 'patient3',
            'address' => 'patient3 address',
            'phone' => '123456789',
            'birth_date' => '2003-03-03',
            'insurance_expiration_date' => '2003-03-03',
            'insurance_company_id' => 3,
            'insurance_number' => '33333333',
            'sex' => 1,
            'marital_status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('patients', [
            'name' => 'patient4',
            'address' => 'patient4 address',
            'phone' => '123456789',
            'birth_date' => '2004-04-04',
            'insurance_expiration_date' => '2004-04-04',
            'insurance_company_id' => 4,
            'insurance_number' => '44444444',
            'sex' => 1,
            'marital_status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        $this->delete($this->tableName, [
            'email' => 'admin@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'doctor@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'patient@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'nurse@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'pharmacist@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'laboratorist@wonderslab.com',
        ]);

        $this->delete($this->tableName, [
            'email' => 'receptionist@wonderslab.com',
        ]);
    }
}
