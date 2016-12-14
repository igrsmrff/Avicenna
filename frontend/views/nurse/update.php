<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Nurse */

$this->title = 'Update Nurse: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Nurses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="nurse-update">
    <?= $this->render('_form', [
        'userModel' => false,
        'model' => $model,
        'departments' => $departments,
        'available_users' => $available_users
    ]) ?>
</div>
