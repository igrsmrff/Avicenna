<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Appointment;
use common\models\Initialinspection;
use common\models\Prescription;
use common\models\Report;
use common\models\Invoice;
use common\models\Payment;
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

            //id
            [
                'attribute' => 'id',
                'label' => 'ID',
                'encodeLabel' => false,
            ],

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

            // 'creator_id',
            [
                'attribute' => 'creator_id',
                'label' => 'Created by',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/' .
                    User::$roles[$model->creator->role] . '/view?id=' .
                    $model->creatorEntity->id . '">' .
                    $model->creatorEntity->name . '</a>';
                }
            ],

            'time:time',
            'date:date',

            [
                'attribute' => 'updated_at',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'attribute' => 'created_at',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
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
                                '/invoice/?InvoiceSearch%5Bappointment_id%5D=' . $model->id,
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
                        return $model->creator_id == Yii::$app->user->identity->id;
                    },
                    'delete' => function ($model, $key, $index) {
                        return $model->creator_id == Yii::$app->user->identity->id;
                    }
                ]
            ],
        ],
    ]);
    ?>
</div>
