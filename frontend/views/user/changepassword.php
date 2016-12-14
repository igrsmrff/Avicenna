<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Change account password: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => $breadcrumbsTitle, 'url' => ['index']];
if ($breadcrumbsTitle == 'Accounts') {
    $this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
}
$this->params['breadcrumbs'][] = 'Change password';

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'oldpass')->textInput(['type'=>'password','class'=>'form-control'])->label('Old password') ?>
    <?= $form->field($model, 'newpass')->textInput(['type'=>'password', 'class'=>'form-control'])->label('New password') ?>
    <?= $form->field($model, 'repeatnewpass')->textInput(['type'=>'password', 'class'=>'form-control'])->label('Repeat new password') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
