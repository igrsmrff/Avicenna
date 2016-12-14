<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Invoice;
use common\models\Payment;
use common\custom\Bookmarks;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <p>
        <?php
        if( Invoice::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Create Invoice', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'invoice_number',
            'due_date:date',

            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Invoice::STATUSES,
                'value' => function ($model) {
                    if ($model->status == Invoice::STATUS_PAID) {
                        return '<a class = "link-underline" href="/payment/view?id=' .
                        $model->payment->id .
                        '">' . Invoice::STATUSES[$model->status] . '</a>';
                    }
                    return Invoice::STATUSES[$model->status];
                }
            ],

            // 'vat_percentage',
            'sub_total_amount',
            'discount_amount',
            'total_invoice_amount',

            //appointment_id
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/patient/view?id=' .
                    $model->appointment->id . '">' .
                    $model->appointment->id . '</a>';
                }
            ],


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
                    {printinvoice}
                    {payinvoice} 
                    {view} 
                    {update} 
                    {delete}
                ',
                'buttons' => [
                    'payinvoice' => function ($url, $model) {
                        $payment = $model->payment;
                        if ($payment) {
                            return Html::a('<i class="fa fa-usd"></i>',
                                '/payment/view?id=' . $payment->id,
                                ['title' => Yii::t('yii', 'View Payment for this invoice'), 'style' => 'color:#9E7E8A']
                            );

                        } else {
                            if (Payment::abilityCreate(Yii::$app->user->identity->role)) {
                                return Html::a('<i class="fa fa-usd"></i>',
                                    '/payment/create-payment?id=' . $model->id,
                                    ['title' => Yii::t('yii', 'Create Payment for this invoice')]
                                );
                            } else {
                                return '';
                            }
                        }
                    },
                    'printinvoice' => function($url, $model) {
                        return Html::a('<i class="fa fa-print"></i>',
                            '/invoice/print-invoice?id=' . $model->id,
                            ['title' => Yii::t('yii', 'Print Invoice')]
                        );
                    }

                ],
                'visibleButtons' => [
                    'payinvoice' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks(Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['payments'];
                    },
                    'update' => function ($model, $key, $index) {
                        return Invoice::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Invoice::abilityCreate(Yii::$app->user->identity->role);
                    },
                ]
            ],
        ],
    ]);
    ?>




</div>
