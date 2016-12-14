<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pharmacist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pharmacist-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    if($userModel) {
        echo $form->field($userModel, 'username')->textInput();
        echo $form->field($userModel, 'email')->textInput();
        echo $form->field($userModel, 'password')->textInput(['type'=>'password']);
    }
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>


    <?php
    if($available_users!==false) {
        if ( !count($available_users) ) {$available_users[0] = 'There are no available users';}
        echo $form->field($model, 'user_id')->dropDownList($available_users)->label('Link account');
    }
    ?>

    <!--    <//?= $form->field($model, 'pharmacistImageUrl')->textInput(['maxlength' => true]) ?>-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
