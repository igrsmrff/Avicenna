<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InvoiceEntryDropDown */

$this->title = 'Create Invoice Entry Drop Down';
$this->params['breadcrumbs'][] = ['label' => 'Invoice Entry Drop Downs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-entry-drop-down-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
