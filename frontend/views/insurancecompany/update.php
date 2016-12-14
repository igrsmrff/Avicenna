<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InsuranceCompany */

$this->title = 'Update Insurance Company: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Insurance Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="insurance-company-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
