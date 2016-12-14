<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pharmacist */

$this->title = 'Update Pharmacist: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pharmacists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pharmacist-update">
    <?= $this->render('_form', [
        'model' => $model,
        'userModel' => false,
        'available_users' => $available_users
    ]) ?>
</div>
