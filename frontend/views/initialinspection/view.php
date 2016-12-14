<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Initialinspection;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Initialinspection */

$this->title = 'Initial Inspection for appointment № ' . $model->id . ' patient: ' . $model->patient->name;
$this->params['breadcrumbs'][] = ['label' => 'Initial Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->patient->name;
?>
<div class="initialinspection-view">

    <p>
        <?php
        if ( Initialinspection::abilityCreate(Yii::$app->user->identity->role) ) {
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

            // 'appointment_id',
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/appointment/view?id=' .
                    $model->appointment->id .'">' .
                    $model->appointment->id . '</a>'
            ],

            // 'patient_id',
            [
                'attribute' => 'patient_name',
                'label' => 'Patient',
                'format' => 'raw',
                'value' =>
                    '<a class = "link-underline" href="/patient/view?id=' .
                    $model->patient->id .'">' .
                    $model->patient->name . '</a>'
            ],

            'weight',
            'height',
            'blood_pressure',
            'temperature',
            'notes:ntext',

            // 'creator_id',
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
