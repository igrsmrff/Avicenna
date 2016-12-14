<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = 'Update Report: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="report-update">
    <?= $this->render('_form', [
        'model' => $model,
        'patientsList' => $patientsList,
        'createFrom' => $createFrom
    ]) ?>
</div>
