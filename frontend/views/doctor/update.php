<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Doctor */

$this->title = 'Update Doctor: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="doctor-update">
    <?= $this->render('_form', [
        'model' => $model,
        'userModel' => false,
        'departments' => $departments,
        'available_users' => $available_users
    ]) ?>
</div>
