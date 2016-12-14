<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Department */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-view">

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
            'title',
            'description',

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
