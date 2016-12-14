<?php
use yii\helpers\Html;
use common\models\Admin;
use common\models\Invoice;

/* @var $model common\models\Invoice */

$this->registerCssFile('@web/css/paper.css');
$this->registerJsFile(Yii::getAlias('@web/js/invoicePrint.js'), ['depends' => [
    'yii\web\YiiAsset',
    'yii\bootstrap\BootstrapAsset'],
]);
$this->title = 'Print Invoice';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-paper">

    <p>
        <?php
            echo Html::button('Print invoice!', ['class' => 'btn btn-success print-btn']);
        ?>
       
    </p>

    <div class="row">
        <div class="col-xs-12">
            <div class="a4">

                <table width="100%" border="0">
                    <tbody><tr>
                        <td width="50%"><img src="/logo.png" style="max-height:80px;"></td>
                        <td align="right">
                            <h4><?= $invoice->attributeLabels()['invoice_number'] . ' : ' . $invoice->invoice_number ?></h4>
                            <h5>Issue Date : <?= date('Y-m-d', $invoice->created_at) ?></h5>
                            <h5><?= $invoice->attributeLabels()['due_date'] . ' : ' .  $invoice->due_date ?></h5>
                            <h5><?= $invoice->attributeLabels()['status'] . ' : ' .  Invoice::STATUSES[$invoice->status] ?></h5>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr>


                <table width="100%" border="0">
                    <tbody>
                    <tr>
                        <td align="left"><h4>Payment To </h4></td>
                        <td align="right"><h4>Bill To </h4></td>
                    </tr>

                    <tr>
                        <td align="left" valign="top">
                            <span class="capitalize"><?= $system->system_title ?></span><br>
                            <span class="capitalize"><?= $system->system_name ?></span><br>
                            <span class="capitalize"><?= Admin::getPhoneCountryCode() . $system->phone ?></span>
                        </td>
                        <td align="right" valign="top">
                            <span class="capitalize">Patient</span><br>
                            <span class="capitalize"><?= $invoice->appointment->patient->name ?></span><br>
                            <span class="capitalize"><?= Admin::getPhoneCountryCode() . $invoice->appointment->patient->phone ?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <hr>


                <h4>Invoice Entries</h4>
                <table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th width="60%">Entry</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($invoiceEntriesAmount as $key=>$data) : ?>
                            <tr>
                                <td class="text-center"><?= $key+1 ?></td>
                                <td><?= $data['entery'] ?></td>
                                <td><?= $data['amount'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


                <table width="100%" border="0">
                    <tbody><tr>
                        <td align="right" width="80%"><?= $invoice->attributeLabels()['sub_total_amount'] . ' : ' ?></td>
                        <td align="right"><?= $invoice->sub_total_amount ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="80%"><?= $invoice->attributeLabels()['vat_percentage'] . ' : ' ?></td>
                        <td align="right"><?= $invoice->vat_percentage ?></td>
                    </tr>
                    <tr>
                        <td align="right" width="80%"><?= $invoice->attributeLabels()['discount_amount_percent'] . ' : ' ?></td>
                        <td align="right"><?= $invoice->discount_amount_percent ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr style="margin:0px;"></td>
                    </tr>
                    <tr>
                        <td align="right" width="80%"><h4><?= $invoice->attributeLabels()['total_invoice_amount'] . ' : ' ?></h4></td>
                        <td align="right"><h4><?= $invoice->total_invoice_amount ?></h4></td>
                    </tr>
                    </tbody></table>


            </div>
        </div>
    </div>

</div>
