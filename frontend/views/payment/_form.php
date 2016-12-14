<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'payment_method')->dropDownList($paymentMethodList) ?>

        <?php
            if(!$invoicesList) {$invoicesList[0] = 'There are no invoices to pay'; }
            echo $form->field($model, 'invoice_id')->dropDownList($invoicesList, ['disabled' => ($createFrom) ? 'disabled' : false]);
        ?>


        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
</div>
