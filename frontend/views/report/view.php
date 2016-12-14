<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Report;

/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">

    <p>
        <?php
            if ( Report::abilityCreate(Yii::$app->user->identity->role) ) {
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
            'description:ntext',

            //'patient_id',
            [
                'attribute' => 'patient_id',
                'label' => 'Patient',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/patient/view?id='.
                    $model->appointment->id .'">' .
                    $model->appointment->patient->name . '</a>'

            ],

            //'creator_id',
            [
                'attribute' => 'creator_id',
                'label' => 'Created by',
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
