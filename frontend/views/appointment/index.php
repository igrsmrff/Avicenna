<?php

use yii\helpers\Html;
use yii\CHtml;
use yii\grid\GridView;
use common\models\User;
use common\models\Appointment;
use common\models\Initialinspection;
use common\models\Report;
use common\models\Prescription;
use common\models\Invoice;
use common\models\Payment;
use common\models\Sms;
use common\custom\Bookmarks;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appointments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-index">

    <p>
        <?php
        if( Appointment::abilityCreate(Yii::$app->user->identity->role)  ) {
            echo Html::a('Create Appointment', ['create'], ['class' => 'btn btn-success']);
        } else {
            echo '<div style="height: 20px"></div>';
        }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',

            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Appointment::STATUSES_ARRAY,
                'value' => function ($model) {
                    return Appointment::STATUSES_ARRAY[$model->status];
                }
            ],

            //patient_id
            [
                'attribute' => 'patient_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/patient/view?id=' .
                    $model->patient->id . '">' .
                    $model->patient->name . '</a>';
                }
            ],

            //doctor_id
            [
                'attribute' => 'doctor_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/doctor/view?id=' .
                    $model->doctor->id . '">' .
                    $model->doctor->name . '</a>';
                }
            ],

            //nurse_id
            [
                'attribute' => 'nurse_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/nurse/view?id=' .
                    $model->nurse->id . '">' .
                    $model->nurse->name . '</a>';
                }
            ],

            [
                'attribute' => 'time',
                'format' => ['time', 'php:H:i']
            ],

            'date:date',

            // 'creator_id',
            [
                'attribute' => 'creator_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/' .
                    User::$roles[$model->creator->role] . '/view?id=' .
                    $model->creatorEntity->id . '">' .
                    $model->creatorEntity->name . '</a>';
                }
            ],

            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
                            {sms}
                            {initialInspection}
                            {report} 
                            {prescription}
                            {invoice}
                            {payment}
                            {view} 
                            {update} 
                            {delete}
                ',
                'buttons' => [
                    'sms' => function ($url, $model) {
                        $sms = $model->sms;
                        if ($sms) {
                            return Html::a('<i class="fa fa-envelope-o"></i>',
                                '/sms/view?id=' . $sms->id,
                                ['title' => Yii::t('yii', 'View SMS reminder for this appointment'), 'style' => 'color:#9E7E8A']
                            );
                        } else {
                            if (Sms::abilityCreate(Yii::$app->user->identity->role)) {
                                return Html::a('<i class="fa fa-envelope-o"></i>',
                                    '/sms/send-sms?id=' . $model->id,
                                    [
                                        'title' => Yii::t('yii', 'Send send SMS reminder now appointment'),
                                        'data-confirm' => Yii::t('yii', 'Are you sure that you want to send an SMS reminder now?')
                                    ]
                                );

                            } else {
                                return '';
                            }
                        }
                    },
                    'initialInspection' => function ($url, $model) {
                        $initialinspection = $model->initialinspection;
                        if ($initialinspection) {
                            return Html::a('<i class="fa fa-stethoscope"></i>',
                                '/initialinspection/view?id=' . $initialinspection->id,
                                ['title' => Yii::t('yii', 'View Initial Inspection for this appointment'), 'style' => 'color:#9E7E8A']
                            );

                        } else {
                            if (Initialinspection::abilityCreate(Yii::$app->user->identity->role)) {
                                return Html::a('<i class="fa fa-stethoscope"></i>',
                                    '/initialinspection/create-initialinspection?id=' . $model->id,
                                    ['title' => Yii::t('yii', 'Create Initial Inspection for this appointment')]
                                );
                            } else {
                                return '';
                            }

                        }
                    },
                    'report' => function ($url, $model) {
                        $report = $model->report;
                        if ($report) {
                            return Html::a('<i class="fa fa-briefcase"></i>',
                                '/report/?ReportSearch%5Bappointment_id%5D=' . $model->id,
                                ['title' => Yii::t('yii', 'View Report for this appointment'), 'style' => 'color:#9E7E8A']
                            );

                        } else {
                            if (Report::abilityCreate(Yii::$app->user->identity->role)) {
                                return Html::a('<i class="fa fa-briefcase"></i>',
                                    '/report/create-report?id=' . $model->id,
                                    ['title' => Yii::t('yii', 'Create new Report for this appointment')]
                                );
                            } else {
                                return '';
                            }
                        }
                    },
                    'prescription' => function ($url, $model) {
                        if($model->report) {
                            if ($model->report->prescription) {
                                return Html::a('<i class="fa fa-check-square-o"></i>',
                                    '/prescription/view?id=' . $model->report->prescription->id,
                                    ['title' => Yii::t('yii', 'View Prescription for this report'), 'style'=>'color:#9E7E8A']
                                );

                            } else {
                                if( Prescription::abilityCreate(Yii::$app->user->identity->role) ) {
                                    return Html::a('<i class="fa fa-check-square-o"></i>',
                                        '/prescription/create-prescription?id=' . $model->report->id,
                                        ['title' => Yii::t('yii', 'Create new Prescription for this report')]
                                    );
                                } else {
                                    return '';
                                }
                            }
                        } else {
                            return '';
                        }
                    },
                    'invoice' => function ($url, $model) {
                        $invoice = $model->invoice;
                        if ($invoice) {
                            return Html::a('<i class="fa fa-book"></i>',
                                '/invoice/view?id=' . $invoice->id,
                                ['title' => Yii::t('yii', 'View Invoice for this appointment'), 'style' => 'color:#9E7E8A']
                            );

                        } else {
                            if (Invoice::abilityCreate(Yii::$app->user->identity->role) ) {
                                return Html::a('<i class="fa fa-book"></i>',
                                    '/invoice/create-invoice?id=' . $model->id,
                                    ['title' => Yii::t('yii', 'Create new Invoice for this appointment')]
                                );
                            } else {
                                return '';
                            }
                        }
                    },
                    'payment' => function ($url, $model) {
                        if( $model->invoice ) {
                            if ($model->invoice->payment) {
                                return Html::a('<i class="fa fa-usd"></i>',
                                    '/payment/?PaymentSearch%5Bid%5D=' . $model->invoice->payment->id,
                                    ['title' => Yii::t('yii', 'View Payment for this appointment'), 'style' => 'color:#9E7E8A']
                                );

                            } else {
                                if (Payment::abilityCreate(Yii::$app->user->identity->role)) {
                                    return Html::a('<i class="fa fa-usd"></i>',
                                        '/payment/create-payment?id=' . $model->invoice->id,
                                        ['title' => Yii::t('yii', 'Create new Payment for this Invoice')]
                                    );
                                } else {
                                    return '';
                                }
                            }
                        } else {
                            return '';
                        }
                    },
                ],
                'visibleButtons' => [
                    'initialInspection' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['initial_inspection'];
                    },
                    'report' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['reports'];
                    },
                    'prescription' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['prescription'];
                    },
                    'invoice' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['invoices'];
                    },
                    'payment' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['payments'];
                    },
                    'update' => function ($model, $key, $index) {
                        return Appointment::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Appointment::abilityCreate(Yii::$app->user->identity->role);
                    }
                ]
            ],
        ],
    ]);
    ?>
</div>

<div class="appointment-index">

    <!--https://github.com/philippfrenzel/yii2fullcalendar-->
    <div class="custom-fullcalendar-container">
        <?= yii2fullcalendar\yii2fullcalendar::widget([
            'loading' => 'Anicenna me events...',
            'header' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'month,basicWeek,basicDay'
            ],
            'events' => $events,
            'options' => [
                'language' => 'en'
            ],
        ]);
        ?>
    </div>

</div>
