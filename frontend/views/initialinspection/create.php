<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Initialinspection */

$this->title = 'Create Initialinspection';
$this->params['breadcrumbs'][] = ['label' => 'Initial Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="initialinspection-create">
    <?= $this->render('_form', [
        'model' => $model,
        'appointmentsList' => $appointmentsList,
        'createFrom' => $createFrom
    ]) ?>
</div>
