<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Doctor */

$this->title = 'Create Doctor';
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="doctor-create">
    <?= $this->render('_form', [
        'model' => $doctorModel,
        'userModel' => $userModel,
        'departments' => $departments,
        'available_users' => false
    ]) ?>
</div>
