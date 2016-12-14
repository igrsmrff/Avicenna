<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Receptionist */

$this->title = 'Create Receptionist';
$this->params['breadcrumbs'][] = ['label' => 'Receptionists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receptionist-create">
    <?= $this->render('_form', [
        'model' => $pharmaModel,
        'userModel' => $userModel,
        'available_users' => false
    ]) ?>
</div>
