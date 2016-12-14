<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Payment;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">

    <p>
        <?php
        if( Payment::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Create Payment', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',

            //'invoice_id',
            [
                'attribute' => 'invoice_number',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/invoice/view?id='.
                    $model->invoice->id .'">' .
                    $model->invoice->invoice_number . '</a>';
                }
            ],

            //'total_invoice_amount',
            [
                'attribute' => 'total_invoice_amount',
                'label' => 'Total Invoice Amount $',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->invoice->total_invoice_amount;
                }
            ],

            //'payment_method',
            [
                'attribute' => 'payment_method',
                'format' => 'raw',
                'filter' => Payment::PAYMENT_METHODS_ARRAY,
                'value' => function ($model) {
                    return  Payment::PAYMENT_METHODS_ARRAY[$model->payment_method];
                }

            ],

            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Payment::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Payment::abilityCreate(Yii::$app->user->identity->role);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
