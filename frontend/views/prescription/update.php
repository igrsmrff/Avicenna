<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Prescription */

$this->title = 'Update Prescription №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prescriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prescription-update">
    <?= $this->render('_form', [
        'model' => $model,
        'reportList' => $reportList,
        'createFrom' => $createFrom
    ]) ?>
</div>
