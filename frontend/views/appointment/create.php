<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Appointment */

$this->title = 'Create Appointment';
$this->params['breadcrumbs'][] = ['label' => 'Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-create">
    <?= $this->render('_form', [
        'model' => $model,
        'patientsList' => $patientsList,
        'doctorsList' => $doctorsList,
        'nursesList' => $nursesList,
        'statusesList' => $statusesList,
        'createFromPatient' => $createFromPatient,
        'createFromDoctor' => $createFromDoctor,
        'createFromNurse' => $createFromNurse
    ]) ?>
</div>
