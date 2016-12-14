<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Patient;
use common\models\Appointment;
use common\models\InsuranceCompany;
use common\custom\Bookmarks;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PatientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Patients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="patient-index">

    <p>
        <?php
            if( Patient::abilityCreate(Yii::$app->user->identity->role)  ) {
                echo Html::a('Create Patient', ['create'], ['class' => 'btn btn-success']);
            }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',

            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/patient/view?id='.
                    $model->id . '">' .
                    $model->name . '</a>';
                }

            ],

            'address',
            'phone',

            //sex
            [
                'attribute' => 'sex',
                'format' => 'raw',
                'filter'=>Patient::SEX_ARRAY,
                'value' => function ($model) {
                    return Patient::SEX_ARRAY[$model->sex];
                }

            ],

            //marital_status
            [
                'attribute' => 'marital_status',
                'format' => 'raw',
                'filter'=>Patient::MARITAL_STATUS_ARRAY,
                'value' => function ($model) {
                    return Patient::MARITAL_STATUS_ARRAY[$model->marital_status];
                }

            ],

            //insurance_company_id
            [
                'attribute' => 'insurance_company_id',
                'format' => 'raw',
                'filter'=>InsuranceCompany::getInsuranceCompanyListDropDown(),
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/insurancecompany/view?id='.
                    $model->insurance_company_id . '">' .
                    $model->insuranceCompany->company_title . '</a>';
                }

            ],

            'insurance_number',
            'insurance_expiration_date:date',

            [
                'attribute' => 'birth_date',
                'label' => 'Date of Birth',
                'format' => ['date']
            ],
            // 'patientImageUrl:url',


            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
                    {appointment}
                    {view}
                    {update}
                    {delete}
                ',
                'buttons' => [
                    'appointment' => function ($url, $model) {
                        if( Appointment::abilityCreate(Yii::$app->user->identity->role) ) {
                            return Html::a('<i class="fa fa-bell-o"></i>',
                                '/appointment/create-appointment?id=' . $model->id . "&from=patient",
                                ['title' => Yii::t('yii', 'Create new appointment for this patient')]
                            );
                        } else {
                            return '';
                        }
                    },
                ],
                'visibleButtons' => [
                    'appointment' => function ($model, $key, $index) {
                        return Appointment::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'view' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks( Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['patients'];
                    },
                    'update' => function ($model, $key, $index) {
                        return Patient::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Patient::abilityCreate(Yii::$app->user->identity->role);
                    },
                ]
            ],
        ],
    ]);
    ?>
