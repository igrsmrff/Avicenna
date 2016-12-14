<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Prescription;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PrescriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prescriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescription-index">

    <p>
        <?php
        if( Prescription::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Create Prescription', ['create'], ['class' => 'marginL btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',

            //'medication:ntext',
            [
                'attribute' => 'medication',
                'format' => 'ntext',
                'value' => function ($model) {
                    if ( strlen($model->medication) >= 500) {return  substr($model->medication, 0, 500) . '...';}
                    else { return $model->medication;}
                }
            ],

            //'note:ntext',
            [
                'attribute' => 'medication',
                'format' => 'ntext',
                'value' => function ($model) {
                    if ( strlen($model->note) >= 500) {return  substr($model->note, 0, 500) . '...';}
                    else { return $model->note;}
                }
            ],

            //'report_id',
            [
                'attribute' => 'report_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/report/view?id='.
                    $model->report->id . '">' .
                    $model->report->title . '</a>';
                }
            ],

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
                        return Prescription::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Prescription::abilityCreate(Yii::$app->user->identity->role);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
