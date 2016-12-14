<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Receptionist */

$this->title = 'Update Receptionist: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Receptionists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="receptionist-update">
    <?= $this->render('_form', [
        'model' => $model,
        'userModel' => false,
        'available_users' => $available_users
    ]) ?>
</div>
