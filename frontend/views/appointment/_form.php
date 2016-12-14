<?php

use common\models\Patient;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;

$this->registerJsFile(Yii::getAlias('@web/js/appointmentForm.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

/* @var $this yii\web\View */
/* @var $model common\models\Appointment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appointment-form">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'date')->widget(DatePicker::className(),
            [
                'dateFormat' => 'yyyy-MM-dd',
                'class' => 'form-control',
                'options' => ['class' => 'form-control']
            ]
        ) ?>

        <?= $form->field($model, 'time')->textInput() ?>
        <?= $form->field($model, 'status')->dropDownList($statusesList) ?>

        <?php
            if ( !$patientsList ) {$patientsList[0] = 'There are no patients';}
            echo $form->field($model, 'patient_id')->widget(Select2::classname(), [
                'data' => $patientsList,
                'language' => Yii::$app->language,
                'options' => [
                    'placeholder' => 'Select a patient from phone number ...',
                    'disabled' => ($createFromPatient) ? 'disabled' : false
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
         ?>


        <?php
            if ( !$doctorsList ) {$doctorsList[0] = 'There are no doctors';}
            echo $form
                ->field($model, 'doctor_id')
                ->dropDownList($doctorsList, ['disabled' => ($createFromDoctor) ? 'disabled' : false])
        ?>

        <?php
            if ( !$nursesList ) {$nursesList[0] = 'There are no doctors';}
            echo $form
                ->field($model, 'nurse_id')
                ->dropDownList($nursesList, ['disabled' => ($createFromNurse) ? 'disabled' : false])
        ?>


        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
