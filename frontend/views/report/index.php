<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Prescription;
use common\models\Report;
use common\custom\Bookmarks;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reports';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <p>
        <?php
        if( Report::abilityCreate(Yii::$app->user->identity->role) ) {
            echo Html::a('Create Report', ['create'], ['class' => 'marginL btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',

            //'description',
            [
                'attribute' => 'description',
                'format' => 'ntext',
                'value' => function ($model) {
                    if ( strlen($model->description) >= 500) {return  substr($model->description, 0, 500) . '...';}
                    else { return $model->description;}
                }
            ],

            //appointment_id
            [
                'attribute' => 'appointment_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/appointment/view?id='.
                    $model->appointment->id . '">' .
                    $model->appointment->id . '</a>';
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
                'template' => '{prescription}  {view} {update} {delete} ',
                'buttons' => [
                    'prescription' => function ($url, $model) {
                        $report = $model->prescription;
                        if ($report) {
                            return Html::a('<i class="fa fa-check-square-o"></i>',
                                '/prescription/view?id=' . $report->id,
                                ['title' => Yii::t('yii', 'View Prescription for this report'), 'style'=>'color:#9E7E8A']
                            );

                        } else {
                            if( Prescription::abilityCreate(Yii::$app->user->identity->role) ) {
                                return Html::a('<i class="fa fa-check-square-o"></i>',
                                    '/prescription/create-prescription?id=' . $model->id,
                                    ['title' => Yii::t('yii', 'Create new Prescription for this report')]
                                );
                            } else {
                                return '';
                            }

                        }
                    }
                ],
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Report::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'delete' => function ($model, $key, $index) {
                        return Report::abilityCreate(Yii::$app->user->identity->role);
                    },
                    'prescription' => function ($model, $key, $index) {
                        $bokmarks = new Bookmarks( Yii::$app->user->identity->role);
                        $rules = $bokmarks->getRules();
                        return $rules['prescription'];
                    },
                ]
            ],
        ],
    ]); ?>
</div>
