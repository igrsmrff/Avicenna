<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?php
        if( Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            echo Html::a('Create Account', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            //id
            [
                'attribute' => 'id',
                'label' => 'ID',
                'encodeLabel' => false,
            ],
            
            [
                'attribute' => 'username',
                'label' => 'Login',
            ],

            'email:email',

            [
                'attribute' => 'role',
                'format' => 'raw',
                'filter'=>User::$roles,
                'value' => function ($model) {
                    return User::$roles[$model->role];
                }
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter'=>User::$statuses,
                'value' =>
                    function ($model) {
                         return Html::a(User::$statuses[$model->status],
                            '/user/change-status?id=' . $model->id,
                            ['title' => Yii::t('yii', 'Change user status')]);
                }
            ],

            'created_at:date',
            'updated_at:date',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '
                    {view} 
                    {update} 
                    {delete} 
                    {change-password}
                    {schedule}
                ',
                'buttons' => [
                    'change-password' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, [
                            'title' => Yii::t('yii', 'Change password'),
                        ]);
                    },
                    'schedule' => function ($url, $model) {
                        return Html::a('<i class="fa fa-file-text" aria-hidden="true"></i>',
                            '/users-schedule/schedule?user_id=' . $model->id,
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
                    'changepassword' => function ($model, $key, $index) {
                        return Yii::$app->user->identity->role === User::ROLE_ADMIN;
                    },
                    'schedule' => function ($model, $key, $index) {
                        return (Yii::$app->user->identity->role === User::ROLE_ADMIN);
                    },
                ]
            ],
        ],

    ]);
    ?>
</div>
