<?php
namespace common\custom;
use common\models\User;

class Bookmarks
{
    private $rules = [];

    function __construct($role=0)
    {
        switch ($role) {
            case User::ROLE_ADMIN:
                $this->admin();
                break;
            case User::ROLE_DOCTOR:
                $this->doctor();
                break;
            case User::ROLE_PATIENT:
                $this->patient();
                break;
            case User::ROLE_NURSE:
                $this->nurse();
                break;
            case User::ROLE_PHARMACIST:
                $this->pharmacist();
                break;
            case User::ROLE_LABORATORIST:
                $this->laboratorist();
                break;
            case User::ROLE_RECEPTIONIST:
                $this->receptionist();
                break;
            case User::ROLE_ACCOUNTANT:
                $this->accountant();
                break;
            default:
                $this->defaultRule();
        }

    }

    public function getRules()
    {
        return $this->rules;
    }

    private function defaultRule()
    {
        $this->rules['role'] = 'entered as guest';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = false;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = false;
        $this->rules['patients'] = false;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = false;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = false;
        $this->rules['receptionists'] = false;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = false;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = false;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = false;
        $this->rules['messages'] = false;
        $this->rules['reports'] = false;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = false;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = false;
    }

    private function admin()
    {
        $this->rules['role'] = 'entered as admin';
        $this->rules['dashboard'] = true;
        $this->rules['accountants'] = true;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = true;
        $this->rules['doctors'] = true;
        $this->rules['patients'] = true;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = true;
        $this->rules['pharmacists'] = true;
        $this->rules['laboratorists'] = true;
        $this->rules['receptionists'] = true;
        $this->rules['accountant'] = true;
        $this->rules['payments'] = true;
        $this->rules['invoice_entries'] = true;
        $this->rules['notices'] = true;
        $this->rules['invoices'] = true;
        $this->rules['appointments'] = true;
        $this->rules['initial_inspection'] = true;
        $this->rules['prescription'] = true;
        $this->rules['messages'] = true;
        $this->rules['reports'] = true;
        $this->rules['insurancecompany'] = true;
        $this->rules['calendar'] = true;
        $this->rules['sms'] = true;
        $this->rules['my_schedule'] = false;
    }

    private function patient()
    {
        $this->rules['role'] = 'entered as patient';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = true;
        $this->rules['patients'] = false;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = false;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = false;
        $this->rules['receptionists'] = false;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = true;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = true;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = true;
        $this->rules['messages'] = true;
        $this->rules['reports'] = true;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = false;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = false;
    }

    private function doctor()
    {
        $this->rules['role'] = 'entered as doctor';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = true;
        $this->rules['patients'] = false;
        $this->rules['doctors_patients'] = true;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = true;
        $this->rules['pharmacists'] = true;
        $this->rules['laboratorists'] = true;
        $this->rules['receptionists'] = true;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = true;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = true;
        $this->rules['invoices'] = true;
        $this->rules['appointments'] = true;
        $this->rules['initial_inspection'] = true;
        $this->rules['prescription'] = true;
        $this->rules['messages'] = true;
        $this->rules['reports'] = true;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = true;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = true;
    }

    private function nurse()
    {
        $this->rules['role'] = 'entered as nurse';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = true;
        $this->rules['patients'] = false;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = true;
        $this->rules['nurses'] = true;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = true;
        $this->rules['receptionists'] = true;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = false;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = true;
        $this->rules['initial_inspection'] = true;
        $this->rules['prescription'] = false;
        $this->rules['messages'] = false;
        $this->rules['reports'] = false;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = true;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = true;
    }

    private function pharmacist()
    {
        $this->rules['role'] = 'entered as pharmacist';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = false;
        $this->rules['patients'] = true;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = false;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = false;
        $this->rules['receptionists'] = true;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = false;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = false;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = true;
        $this->rules['messages'] = false;
        $this->rules['reports'] = true;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = false;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = true;
    }

    private function laboratorist()
    {
        $this->rules['role'] = 'entered as laboratorist';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = false;
        $this->rules['patients'] = true;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = false;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = false;
        $this->rules['receptionists'] = false;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = false;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = false;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = false;
        $this->rules['messages'] = false;
        $this->rules['reports'] = false;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = false;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = true;
    }

    private function  accountant()
    {
        $this->rules['role'] = 'entered as receptionist';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = false;
        $this->rules['patients'] = true;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = false;
        $this->rules['pharmacists'] = false;
        $this->rules['laboratorists'] = false;
        $this->rules['receptionists'] = false;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = true;
        $this->rules['invoice_entries'] = false;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = true;
        $this->rules['appointments'] = false;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = false;
        $this->rules['messages'] = false;
        $this->rules['reports'] = false;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = false;
        $this->rules['sms'] = false;
        $this->rules['my_schedule'] = true;
    }

    private function  receptionist()
    {
        $this->rules['role'] = 'entered as receptionist';
        $this->rules['dashboard'] = false;
        $this->rules['accountants'] = false;
        $this->rules['my_accountant'] = true;
        $this->rules['departments'] = false;
        $this->rules['doctors'] = true;
        $this->rules['patients'] = true;
        $this->rules['doctors_patients'] = false;
        $this->rules['nurses_patients'] = false;
        $this->rules['nurses'] = true;
        $this->rules['pharmacists'] = true;
        $this->rules['laboratorists'] = true;
        $this->rules['receptionists'] = true;
        $this->rules['accountant'] = false;
        $this->rules['payments'] = false;
        $this->rules['invoice_entries'] = true;
        $this->rules['notices'] = false;
        $this->rules['invoices'] = false;
        $this->rules['appointments'] = true;
        $this->rules['initial_inspection'] = false;
        $this->rules['prescription'] = false;
        $this->rules['messages'] = false;
        $this->rules['reports'] = false;
        $this->rules['insurancecompany'] = false;
        $this->rules['calendar'] = true;
        $this->rules['sms'] = true;
        $this->rules['my_schedule'] = true;
    }
}

