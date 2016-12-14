<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EmployeeSchedule */


$this->title = 'Update Day Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedule for ' . $user->name, 'url' => ['schedule?user_id=' . $user->user_id]];
//$this->params['breadcrumbs'][] = ['label' => 'Employee Schedules', 'url' => ['schedule']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update Schedule on ' . $model->date;
?>
<div class="employee-schedule-update">
    <?= $this->render('_form', [
        'model' => $model,
        'usersListDropDown' => $usersListDropDown,
        'createFrom' => $createFrom
    ]) ?>
</div>
