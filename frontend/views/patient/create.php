<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Patient */

$this->title = 'Create Patient';
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="patient-create">
    <?= $this->render('_form', [
        'model' => $model,
        'sexListDropDown' =>  $sexListDropDown,
        'insuranceCompanyListDropDown' => $insuranceCompanyListDropDown,
        'maritalStatusListDropDown' => $maritalStatusListDropDown
    ]) ?>
</div>
