<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Initialinspection;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InitialinspectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Initial Inspections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="initialinspection-index">

    <p>
        <?php
        if( Initialinspection::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Create Initial Inspection', ['create'], ['class' => 'marginL btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',

            // 'appointment_id',
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/appointment/view?id=' .
                    $model->appointment->id .'">' .
                    $model->appointment->id . '</a>';
                }
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
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/'.
                    User::$roles[$model->creator->role]  . '/view?id='.
                    $model->creatorEntity->id .'">' .
                    $model->creatorEntity->name . '</a>';
                }
            ],

            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Initialinspection::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Initialinspection::abilityCreate(Yii::$app->user->identity->role);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
