<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Appointment;


/* @var $this yii\web\View */
/* @var $searchModel common\models\DoctorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Doctors';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="doctor-index">

    <p>
        <?php
            if( Yii::$app->user->identity->role === User::ROLE_ADMIN) {
                echo Html::a('Create Doctor', ['create'], ['class' => 'btn btn-success']);
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
            'profile:ntext',

            [
                'attribute' => 'department_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a class = "link-underline" href="/department/view?id='.
                    $model->department_id . '">' .
                    $model->department->title . '</a>';
                }
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{appointment} {schedule} {view} {update} {delete}',
                'buttons' => [
                    'appointment' => function ($url, $model) {
                        if( Appointment::abilityCreate(Yii::$app->user->identity->role) ) {
                            return Html::a('<i class="fa fa-bell-o active-icon"></i>',
                                '/appointment/create-appointment?id=' . $model->id . '&from=doctor',
                                ['title' => Yii::t('yii', 'Create new appointment for this doctor')]
                            );
                        } else {
                            return '';
                        }
                    },
                    'schedule' => function ($url, $model) {
                        return Html::a('<i class="fa fa-file-text active-icon"></i>',
                            '/users-schedule/schedule?user_id=' . $model->user->id,
                            ['title' => Yii::t('yii', 'Change password')]);
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
    ]);
    ?>
</div>
