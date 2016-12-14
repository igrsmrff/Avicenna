<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\EmployeeSchedule */

$this->title = 'Create Day Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedule for ' . $user->name, 'url' => ['schedule?user_id=' . $user->user_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-schedule-create">
    <?= $this->render('_form', [
        'model' => $model,
        'usersListDropDown' => $usersListDropDown,
        'createFrom' => $createFrom
    ]) ?>
</div>
