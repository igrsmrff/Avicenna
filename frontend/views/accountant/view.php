<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Accountant;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Accountant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Accountants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accountant-view">

    <p>
        <?php
        if (Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger marginL',
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

            [
                'label' => 'Login',
                'value' => Accountant::findOne($model->id )->user->username
            ],

            [
                'label' => 'Email',
                'value' => Accountant::findOne($model->id )->user->email
            ],

            'name',
            'address',
            'phone',
            'accountantImageUrl:url',

            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['datetime']
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['datetime']
            ]
        ],
    ]) ?>

</div>
