<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Initialinspection */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="initialinspection-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'blood_pressure')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'temperature')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?php
        if (!$appointmentsList) { $appointmentsList[0] = 'There are no appointments'; }
        echo $form
            ->field($model, 'appointment_id')
            ->dropDownList($appointmentsList, ['disabled' => ($createFrom) ? 'disabled' : false])
    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
