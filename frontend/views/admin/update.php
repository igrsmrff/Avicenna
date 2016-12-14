<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title = 'Update Admin: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
