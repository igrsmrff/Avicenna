<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Prescription */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prescription-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'medication')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

        <?php
            if(!$reportList) {$reportList[0] = 'There are no reports'; }
            echo $form->field($model, 'report_id')
                ->dropDownList($reportList, ['disabled' => ($createFrom) ? 'disabled' : false])
        ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
