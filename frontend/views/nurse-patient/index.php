<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Patient;
use common\models\InsuranceCompany;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NursePatientSerach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Patients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nurse-patient-pivot-index">

    <div style="height: 20px"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->patient->id;
                }

            ],

            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/patient/view?id='. $model->patient_id .
                    '">' . $model->patient->name . '</a>';
                }

            ],

            //'address',
            [
                'attribute' => 'address',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->patient->address;
                }

            ],

            //'address',
            [
                'attribute' => 'phone',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->patient->phone;
                }

            ],

            //sex
            [
                'attribute' => 'sex',
                'format' => 'raw',
                'filter'=> Patient::SEX_ARRAY,
                'value' => function ($model) {
                    return Patient::SEX_ARRAY[$model->patient->sex];
                }

            ],

            //marital_status
            [
                'attribute' => 'marital_status',
                'format' => 'raw',
                'filter'=>Patient::MARITAL_STATUS_ARRAY,
                'value' => function ($model) {
                    return Patient::MARITAL_STATUS_ARRAY[$model->patient->marital_status];
                }

            ],

            //insurance_company_id
            [
                'attribute' => 'insurance_company',
                'format' => 'raw',
                'filter'=>InsuranceCompany::getInsuranceCompanyListDropDown(),
                'value' => function ($model) {
                    $company = $model->patient->getInsuranceCompany()->one();
                    return '<a class = "link-underline" href="/insurancecompany/view?id='.
                    $company->id . '">' .
                    $company->company_title . '</a>';
                }

            ],

            //insurance_number
            [
                'attribute' => 'insurance_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->patient->insurance_number;
                }

            ],

            //insurance_expiration_date:date
            [
                'attribute' => 'insurance_expiration_date',
                'format' => 'date',
                'value' => function ($model) {
                    return $model->patient->insurance_expiration_date;
                }

            ],


            //created_at
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'value' => function ($model) {
                    return $model->patient->created_at;
                }

            ],

            //updated_at
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'value' => function ($model) {
                    return $model->patient->updated_at;
                }

            ],

            //'doctor_id',
            // 'patient_id',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
                    {appointment} 
                    {view} 
                    {update} 
                    {delete} 
                ',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                            '/patient/view?id=' . $model->patient->id,
                            ['title' => Yii::t('yii', 'View patient')]
                        );
                    },
                    'appointment' => function ($url, $model) {
                        return Html::a('<i class="fa fa-bell-o"></i>',
                            '/appointment/create-appointment-patient?patient_id=' .
                            $model->patient->id . "&from=nurse",
                            ['title' => Yii::t('yii', 'Create new appointment for this my patient')]
                        );
                    },
                ],
                'visibleButtons' => [
                    'view' => function ($model, $key, $index) {
                        return true;
                    },
                    'appointment' => function ($model, $key, $index) {
                        return false;
                    },
                    'update' => function ($model, $key, $index) {
                        return false;
                    },
                    'delete' => function ($model, $key, $index) {
                        return true;
                    },
                ]
            ],
        ],
    ]); ?>
</div>
