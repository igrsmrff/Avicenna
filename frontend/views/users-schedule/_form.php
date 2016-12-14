<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EmployeeSchedule */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-schedule-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'date')->textInput(['disabled'=>true]) ?>
        <?= $form->field($model, 'start_time')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'end_time')->textInput(['maxlength' => true]) ?>

         <?= $form
            ->field($model, 'user_id')
            ->dropDownList($usersListDropDown, ['disabled' => ($createFrom) ? 'disabled' : false])
         ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
