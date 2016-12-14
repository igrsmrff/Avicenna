<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput()->label('Login') ?>
    <?= $form->field($model, 'email')->textInput() ?>

    <?php
        if($model->isNewRecord){
            echo $form->field($model, 'password')->textInput(['type'=>'password']);
        }
    ?>

    <?php
    if($model->isNewRecord) {
        $roles[0] = 'Select Role';
        echo $form->field($model, 'role')->dropDownList(
            $roles
        );
    }
    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
