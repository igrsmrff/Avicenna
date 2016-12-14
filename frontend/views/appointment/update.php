<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Appointment */

$this->title = 'Update Appointment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="appointment-update">
    <?= $this->render('_form', [
        'model' => $model,
        'statusesList' => $statusesList,
        'patientsList' => $patientsList,
        'doctorsList' => $doctorsList,
        'nursesList' => $nursesList,
        'createFromPatient' => $createFromPatient,
        'createFromDoctor' => $createFromDoctor,
        'createFromNurse' => $createFromNurse
    ]) ?>
</div>
