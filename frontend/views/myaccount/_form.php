<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="myaccount-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        if($accountModel) {
            echo $form->field($accountModel, 'username')->textInput();
            echo $form->field($accountModel, 'email')->textInput();
        }

        if ($entityModel) {
            foreach ($modelFields as $key => $value) {

                if($value['type']== 'dropDownList') {
                    echo $form->field($entityModel, $value['title'])->$value['type']($value['list'])->label($value['label']);
                } else {
                    echo $form->field($entityModel, $value['title'])->$value['type']();
                }

            }
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>