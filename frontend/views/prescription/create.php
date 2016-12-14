<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Prescription */

$this->title = 'Create Prescription';
$this->params['breadcrumbs'][] = ['label' => 'Prescriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescription-create">
    <?= $this->render('_form', [
        'model' => $model,
        'reportList' => $reportList,
        'createFrom' => $createFrom
    ]) ?>
</div>