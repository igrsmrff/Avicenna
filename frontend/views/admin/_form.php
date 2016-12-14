<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); exit;?>

    <?= $form->field($model, 'system_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'system_title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'paypal_email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone_сountry_code')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'system_email')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'adminImageUrl')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'language')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
