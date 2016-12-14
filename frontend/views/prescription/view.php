<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Prescription;

/* @var $this yii\web\View */
/* @var $model common\models\Prescription */

$this->title = 'Prescription №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prescriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescription-view">

    <p>
        <?php
        if ( Prescription::abilityCreate(Yii::$app->user->identity->role) ) {
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
            'medication:ntext',
            'note:ntext',

            //'report_id',
            [
                'attribute' => 'report_id',
                'format' => 'raw',
                'value' =>
                    '<a class = "link-underline" href="/report/view?id='.
                    $model->report->id . '">' .
                    $model->report->title . '</a>'
            ],

            // 'creator_id',
            [
                'attribute' => 'creator_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/'.
                    User::$roles[$model->creator->role]  . '/view?id='.
                    $model->creatorEntity->id .'">' .
                    $model->creatorEntity->name . '</a>'
            ],

            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
