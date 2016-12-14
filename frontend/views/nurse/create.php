<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Nurse */

$this->title = 'Create Nurse';
$this->params['breadcrumbs'][] = ['label' => 'Nurses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nurse-create">
    <?= $this->render('_form', [
        'userModel' => $userModel,
        'model' => $nurseModel,
        'departments' => $departments,
        'available_users' => false
    ]) ?>
</div>
