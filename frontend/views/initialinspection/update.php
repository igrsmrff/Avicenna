<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Initialinspection */

$this->title = 'Update Initial Inspection for patient: ' . $model->getPatient()->one()->name;
$this->params['breadcrumbs'][] = ['label' => 'Initial Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getPatient()->one()->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="initialinspection-update">
    <?= $this->render('_form', [
        'model' => $model,
        'appointmentsList' => $appointmentsList,
        'createFrom' => $createFrom
    ]) ?>
</div>
