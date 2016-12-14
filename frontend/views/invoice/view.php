<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Invoice;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = $model->invoice_number;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-view">

    <p>
        <?php
        if ( Invoice::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger marginL',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }

        if($model->status == Invoice::STATUS_PAID) {
            $linkStatus = '<a class = "link-underline" href="/payment/view?id='.
            $model->payment->id . '">' .
            Invoice::STATUSES[$model->status] . '</a>';
        } else $linkStatus = Invoice::STATUSES[$model->status];
        ?>
    </p>

    <h3 class="text-center">Invoice</h3>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'id',
            'invoice_number',
            'due_date:date',

            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $linkStatus
            ],

            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id=' .
                    $model->appointment->id . '">' .
                    $model->appointment->id . '</a>'
            ],

            //'patient_id',
            [
                'attribute' => 'patient_id',
                'label' => 'Patient',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id='.
                    $model->appointment->patient->id .'">' .
                    $model->appointment->patient->name . '</a>'
            ],

            //'creator_id',
            [
                'attribute' => 'creator_id',
                'label' => 'Created by',
                'format' => 'raw',
                'value' =>
                    '<a class = "link-underline" href="/'.
                    User::$roles[$model->creator->role]  . '/view?id='.
                    $model->creatorEntity->id .'">' .
                    $model->creatorEntity->name . '</a>'
            ],

           'created_at:date',
           'updated_at:date',
        ],
    ]) ?>

    <h3 class="text-center">Invoice Entries</h3>

        <table id="w0" class="table table-striped table-bordered detail-view">
            <tbody>
            <?php foreach ($invoiceEntriesAmount as $i => $data) : ?>
                <tr>
                    <th><?= $data->invoiceEntryDropDown->title ?></th>
                    <td><?= $data->amount ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th><?= $model->attributeLabels()['sub_total_amount'] ?></th>
                <td><?= $model->sub_total_amount ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['vat_percentage'] ?></th>
                <td><?= $model->vat_percentage ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['discount_amount_percent'] . ' / ' . $model->attributeLabels()['discount_amount'] ?></th>
                <td><?= $model->discount_amount_percent . ' / ' . $model->discount_amount  ?></td>
            </tr>
            <tr>
                <th><?= $model->attributeLabels()['total_invoice_amount'] ?></th>
                <td><?= $model->total_invoice_amount ?></td>
            </tr>

            </tbody>
        </table>



</div>
