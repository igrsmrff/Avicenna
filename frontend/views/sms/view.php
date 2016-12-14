<?php

use common\models\User;
use common\models\Sms;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Sms */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sms-view">

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'text_message:ntext',

            //'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => Sms::STATUSES_ARRAY[$model->status]
            ],

            //'appointment_id',
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/appointment/view?id=' .
                    $model->appointment->id . '">' .
                    $model->appointment->id . '</a>'
            ],

            'message_identifier',

            // 'creator_id',
            [
                'attribute' => 'creator_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/' .
                    User::$roles[$model->creator->role] . '/view?id=' .
                    $model->creatorEntity->id . '">' .
                    $model->creatorEntity->name . '</a>'
            ],

            'created_at:date',
        ],
    ]) ?>

</div>
