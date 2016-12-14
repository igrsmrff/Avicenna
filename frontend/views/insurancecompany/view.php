<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\InsuranceCompany */

$this->title = $model->company_title;
$this->params['breadcrumbs'][] = ['label' => 'Insurance Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-view">

    <p>
        <?php
        if ( User::ROLE_ADMIN === (Yii::$app->user->identity->role) ) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'marginL btn btn-primary']);
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'marginL btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'company_title',
            'company_description',
            'companyImageUrl:url',
            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['date']
            ],
        ],
    ]) ?>

</div>
