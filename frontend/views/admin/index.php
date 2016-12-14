<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <p>
        <?= Html::a('Create Admin', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'attribute' => 'username',
                'label' => 'Login',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],

            [
                'attribute' => 'email',
                'label' => 'Email',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->user->email;
                }
            ],

            'system_name',
            'system_title',
            'name',
            'address',
             'paypal_email:email',
            // 'currency',
            // 'phone',
             'system_email:ntext',
            // 'adminImageUrl:url',
            // 'user_id',
             'language',

            // 'created_at',
            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['datetime']
            ],

            // 'updated_at',
            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['datetime']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
