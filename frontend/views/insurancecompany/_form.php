<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InsuranceCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="insurance-company-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'company_title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'company_description')->textInput(['maxlength' => true]) ?>

        <!-- <//?= $form->field($model, 'companyImageUrl')->textInput(['maxlength' => true]) ?> -->


        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
