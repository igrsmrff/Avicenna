<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Accountant */

$this->title = 'Create Accountant';
$this->params['breadcrumbs'][] = ['label' => 'Accountants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accountant-create">
    <?= $this->render('_form', [
        'model' => $accountModel,
        'userModel' => $userModel,
        'available_users' => false
    ]) ?>
</div>
