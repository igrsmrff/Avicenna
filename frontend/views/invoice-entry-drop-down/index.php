<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InvoiceEntryDropDownSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-entry-drop-down-index">

    <p>
        <?= Html::a('Create Invoice Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            'created_at:date',
            'updated_at:date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
