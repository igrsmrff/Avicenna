<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Sms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text_message')->textarea(['rows' => 6]) ?>

    <?php
    if ( !$appointmentsList ) {$patientsList[0] = 'There are no appointments';}
    echo $form->field($model, 'appointment_id')->widget(Select2::classname(), [
        'data' => $appointmentsList,
        'language' => Yii::$app->language,
        'options' => [
            'placeholder' => 'Select an Appointment ...',
            'disabled' => ($createFrom) ? 'disabled' : false
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
