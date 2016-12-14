<?php

use yii\helpers\Html;
use common\models\User;
use common\models\Patient;

/* @var $this yii\web\View */
/* @var $model common\models\Patient */

$this->title = 'Update Patient: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="patient-update">
    <?= $this->render('_form', [
        'model' => $model,
        'sexListDropDown' =>  $sexListDropDown,
        'insuranceCompanyListDropDown' => $insuranceCompanyListDropDown,
        'maritalStatusListDropDown' => $maritalStatusListDropDown
    ]) ?>
</div>

