<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Payment;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->title = 'Payment № ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-view">

    <p>
        <?php
        if ( Payment::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger marginL',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'id',

            //'invoice_id',
            [
                'attribute' => 'invoice_id',
                'label' => 'Invoice',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/invoice/view?id='.
                    $model->invoice->id .'">' .
                    $model->invoice->invoice_number . '</a>'
            ],

            //'total_invoice_amount',
            [
                'attribute' => 'total_invoice_amount',
                'label' => 'Invoice amount',
                'format' => 'raw',
                'value' => $model->invoice->total_invoice_amount
            ],

            //'payment_method',
            [
                'attribute' => 'payment_method',
                'format' => 'raw',
                'filter' => Payment::PAYMENT_METHODS_ARRAY,
                'value' => Payment::PAYMENT_METHODS_ARRAY[$model->payment_method]
            ],

            'created_at:date',
            'updated_at:date'
        ],
    ]) ?>

</div>
