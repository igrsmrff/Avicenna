<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Sms;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-index">

    <p>
        <?= Html::a('Create new Sms', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'text_message:ntext',

            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => Sms::STATUSES_ARRAY,
                'value' => function ($model) {
                    return Sms::STATUSES_ARRAY[$model->status];
                }
            ],

            //'appointment_id',
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/appointment/view?id=' .
                    $model->id . '">' .
                    $model->id . '</a>';
                }
            ],

            'message_identifier',

            // 'creator_id',
            [
                'attribute' => 'creator_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/' .
                    User::$roles[$model->creator->role] . '/view?id=' .
                    $model->creatorEntity->id . '">' .
                    $model->creatorEntity->name . '</a>';
                }
            ],

            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return false;
                    }
                ]
            ],
        ],
    ]); ?>
</div>
