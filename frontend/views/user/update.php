<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Update Account: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles
    ]) ?>
</div>
