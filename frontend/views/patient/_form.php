<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Patient */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="patient-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput() ?>
    <?= $form->field($model,'birth_date')->widget(DatePicker::className(),
        [
            'dateFormat' => 'yyyy-MM-dd',
            'class' => 'form-control',
            'options' => ['class' => 'form-control']
        ]
    ) ?>

    <?php
        if($model->isNewRecord) { array_unshift( $sexListDropDown, 'Select Sex'); }
        echo $form->field($model, 'sex')->dropDownList($sexListDropDown)->label('Sex');

        if($model->isNewRecord) { array_unshift( $maritalStatusListDropDown, 'Select Marital Status'); }
        echo $form->field($model, 'marital_status')->dropDownList($maritalStatusListDropDown)->label('Marital Status');

        if($model->isNewRecord) { array_unshift( $insuranceCompanyListDropDown, 'Select Insurance Company'); }
        echo $form->field($model, 'insurance_company_id')->dropDownList($insuranceCompanyListDropDown)->label('Insurance Company');
    ?>
    <?= $form->field($model, 'insurance_number')->textInput() ?>
    <?= $form->field($model, 'insurance_expiration_date')->widget(DatePicker::className(),
        [
            'dateFormat' => 'yyyy-MM-dd',
            'class' => 'form-control',
            'options' => ['class' => 'form-control']
        ]
    ) ?>

    <!--  <//?= $form->field($model, 'patientImageUrl')->textInput(['maxlength' => true]) ?>  -->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
