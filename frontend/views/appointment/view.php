<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Appointment;

/* @var $this yii\web\View */
/* @var $model common\models\Appointment */

$this->title = 'Appointment №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="appointment-view">

    <p>
        <?php
        if ( Appointment::abilityCreate(Yii::$app->user->identity->role) ) {
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
                'attribute' => 'status',
                'value' =>  Appointment::STATUSES_ARRAY[$model->status]
            ],

            //'patient_id',
            [
                'attribute' => 'patient_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id='.
                    $model->patient->id .'">' .
                    $model->patient->name . '</a>'

            ],

            //'doctor_id',
            [
                'attribute' => 'doctor_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id='.
                    $model->doctor->id .'">' .
                    $model->doctor->name . '</a>'

            ],

            //'nurse_id',
            [
                'attribute' => 'nurse_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id='.
                    $model->nurse->id .'">' .
                    $model->nurse->name . '</a>'

            ],

            'date:date',

            [
                'attribute' => 'time',
                'format' => ['time', 'php:H:i']
            ],

            //'creator_id',
            [
                'attribute' => 'creator_id',
                'format' => 'raw',
                'value' =>
                    '<a class = "link-underline" href="/'.
                    User::$roles[$model->creator->role]  . '/view?id='.
                    $model->creatorEntity->id .'">' .
                    $model->creatorEntity->name . '</a>'
            ],

            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
