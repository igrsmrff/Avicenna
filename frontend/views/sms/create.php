<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sms */

$this->title = 'Create Sms';
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-create">
    <?= $this->render('_form', [
        'model' => $model,
        'appointmentsList' => $appointmentsList,
        'createFrom' => $createFrom
    ]) ?>
</div>
