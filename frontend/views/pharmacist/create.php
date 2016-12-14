<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pharmacist */

$this->title = 'Create Pharmacist';
$this->params['breadcrumbs'][] = ['label' => 'Pharmacists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pharmacist-create">
    <?= $this->render('_form', [
        'model' => $pharmaModel,
        'userModel' => $userModel,
        'available_users' => false
    ]) ?>
</div>
