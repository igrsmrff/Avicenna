<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Laboratorist;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Laboratorist */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Laboratorists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorist-view">

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
                'value' => Laboratorist::findOne($model->id )->user->username
            ],

            [
                'label' => 'Email',
                'value' => Laboratorist::findOne($model->id )->user->email
            ],

            'name',
            'address',
            'phone',
            'laboratoristImageUrl:url',
            'created_at:date',
            'updated_at:date'
        ],
    ]) ?>

</div>
