<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NurseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nurses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nurse-index">

    <p>
        <?php
        if( Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            echo Html::a('Create Nurse', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
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
                'format' => 'email',
                'value' => function ($model) {
                    return $model->user->email;
                }
            ],

            'name',
            'address',
            'phone',
            //'nurseImageUrl:url',

            //department_id
            [
                'attribute' => 'department_id',
                'label' => 'Department',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/department/view?id='.
                    $model->department_id . '">' .
                    $model->department->title . '</a>';
                }
            ],

            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{schedule} {view} {update} {delete} ',
                'buttons' => [
                    'schedule' => function ($url, $model) {
                        return Html::a('<i class="fa fa-file-text active-icon"></i>',
                            '/users-schedule/schedule?user_id=' . $model->user->id,
                            ['title' => Yii::t('yii', 'Show schedule')]);
                    }
                ],
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                    },
                    'delete' => function ($model, $key, $index) {
                        return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                    },
                    'schedule' => function ($model, $key, $index) {
                        return (Yii::$app->user->identity->role === User::ROLE_RECEPTIONIST) ||
                               (Yii::$app->user->identity->role === User::ROLE_ADMIN);
                    },
                ]
            ],
        ],
    ]); ?>
</div>
