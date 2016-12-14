<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */

$this->title = 'Update Invoice № ' . $model->invoice_number;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoice_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-update">
    <?= $this->render('_form', [
        'model' => $model,
        'appointmentsList' => $appointmentsList,
        'statusesList' => $statusesList,
        'createFrom' => $createFrom,
        'invoiceEntriesAmount' => $invoiceEntriesAmount,
        'modelEntries' => $modelEntries,
        'entryList' => $entryList
    ]) ?>
</div>
