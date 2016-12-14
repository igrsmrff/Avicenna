<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InvoiceEntryDropDown */

$this->title = 'Update Invoice Entry Drop Down: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Invoice Entry Drop Downs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-entry-drop-down-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
