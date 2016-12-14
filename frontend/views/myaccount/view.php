<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;
use common\models\Department;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = 'My accounts';
?>
<div class="user-view">

    <p>
        <?php
        if (Yii::$app->user->identity->role === User::ROLE_ADMIN ) {
            echo Html::a('Edit Account', ['account', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
            echo Html::a('Edit Profile', ['profile', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
            echo Html::a('Change password', ['changepassword', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);

        }
        ?>
    </p>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',

            [
                'attribute' => 'username',
                'label' => 'Login',
            ],

            'email:email',

            ['attribute' => 'role',
                'format' => 'raw',
                'value' => User::$roles[$model->role]
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => User::$statuses[$model->status]
            ],

            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['date']
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['date']
            ]
        ],
    ]) ?>


    <?php
        $attributes = [];
        foreach ($modelFields as $key => $value) {

            if ($value['title'] == 'birth_date') {
                $attributes[] = [
                    'attribute' => 'Date of Birth',
                    'format' => 'date',
                ];
            } elseif ($value['title'] == 'department_id') {
                $attributes[] = [
                    'label' => 'Department',
                    'format' => 'raw',
                    'value' => '<a class = "link-underline" href="/department/view?id='.
                        $entityModel->department_id . '">' .
                        $entityModel->department->title . '</a>'
                ];
            } elseif ($value['title'] == 'email' || $value['title'] == 'paypal_email' || $value['title'] == 'system_email') {
                $attributes[] = $value['title'] . ':email';
            } else {
                $attributes[] = [
                    'attribute' => $value['title'],
                    'format' => 'raw',
                ];
            }
        }
        echo DetailView::widget([
            'model' => $entityModel,
            'attributes' => $attributes,
        ]);
    ?>


</div>
