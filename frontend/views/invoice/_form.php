<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Invoice;
use common\models\Admin;
use yii\jui\DatePicker;

$this->registerJsFile(Yii::getAlias('@web/js/invoiceForm.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);

/* @var $this yii\web\View */
/* @var $model common\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(['id' => 'invoice-form']); ?>

        <?= $form
            ->field($model, 'invoice_number')
            ->textInput(
                [
                    'maxlength' => true,
                    'value' =>  $model->invoice_number,
                    'disabled' => true
                ]
            )
        ?>

        <?= $form->field($model, 'due_date')->widget(DatePicker::className(),
            [
                'dateFormat' => 'yyyy-MM-dd',
                'class' => 'form-control',
                'options' => ['class' => 'form-control']
            ]
        ) ?>


        <?php
            if($model->isNewRecord) {
                echo '<div class="entries-paretn-div">';
                    echo '<div class="row entries-group-input">';

                        echo '<div class="col-xs-6">';
                            echo $form->field($modelEntries, '[' . 1 . ']entry_id')->dropDownList($entryList);
                        echo '</div>';

                        echo '<div class="col-xs-3">';
                            echo $form->field($modelEntries, '[' . 1 . ']amount')->textInput(['class' => 'amount form-control']);
                        echo '</div>';

                        echo '<div class="col-xs-1">';
                            echo '<div style="height: 65px">';
                                echo '<button type="button" class="btn btn-success entries-btn-append" style="margin-top: 25px">Add more</button>';
                            echo '</div>';
                        echo '</div>';

                    echo '</div>';
                echo '</div>';
            }
        ?>

        <?php echo !$model->isNewRecord ?  '<div class="entries-paretn-div ">' : false ?>

            <?php foreach ($invoiceEntriesAmount as $i => $data) : ?>
                <div class="row entries-group-input">
                    <div class="col-xs-6">
                        <?php $modelEntries->entry_id = $data->entry_id; ?>
                        <?= $form->field($modelEntries, '[' . $i . ']entry_id')->dropDownList($entryList) ?>
                    </div>
                    <div class="col-xs-3">
                        <?= $form->field($modelEntries, '[' . $i . ']amount')->textInput(['value' => $data['amount'], 'class' => 'amount form-control']); ?>
                    </div>
                    <div class="col-xs-1">
                        <div style="height: 65px">
                            <?php
                                if( count($invoiceEntriesAmount)-1  == $i )
                                    echo '<button type="button" class="btn btn-success entries-btn-append" style="margin-top: 25px">Add more</button>';
                                else
                                    echo '<button type="button" class="btn entries-btn-delete btn-danger" style="margin-top: 25px">Remove</button>';
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php echo !$model->isNewRecord ?  '</div>' : false ?>

        <?= $form->field($model, 'sub_total_amount_disabled')
            ->textInput(['class'=> 'sub_total_amount form-control', 'disabled' => true])
            ->label('Sub Total Amount') ?>

        <?= $form->field($model, 'sub_total_amount')->textInput(['class'=> 'sub_total_amount'])->hiddenInput()->label(false); ?>


        <?= $form
            ->field($model, 'vat_percentage')
            ->textInput(
                [
                    'maxlength' => true,
                    'value'=> Admin::getVatPercentage(),
                    'disabled' => true
                ])
        ?>


        <div class="row">
            <div class="col-xs-6">
                <?= $form->field($model, 'discount_amount_percent')->textInput(['maxlength' => true, 'class' => 'discount_amount_percent form-control']) ?>
            </div>

            <div class="col-xs-6">
                <?= $form->field($model, 'discount_amount')->textInput(['maxlength' => true, 'class' => 'discount_amount form-control']) ?>
            </div>
        </div>

        <?= $form->field($model, 'total_invoice_amount')->textInput(['maxlength' => true , 'class'=> 'totalAmount form-control']) ?>

        <?= $form
            ->field($model, 'status')
            ->dropDownList($statusesList, ['disabled' => ($model->isNewRecord) ? 'disabled' : false]) ?>

        <?php
            if ( !count($appointmentsList) ) {
                $appointmentsList[0] = 'There are no appointment';
            }
            echo $form
                ->field($model, 'appointment_id')
                ->dropDownList($appointmentsList, ['disabled' => ($createFrom) ? 'disabled' : false])
        ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
