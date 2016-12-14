<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Nurse;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Nurse */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Nurses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="nurse-view">

    <p>
        <?php
        if (Yii::$app->user->identity->role === User::ROLE_ADMIN) {
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

            [
                'label' => 'Login',
                'value' => $model->user->username,
            ],

            [
                'label' => 'Email',
                'value' => $model->user->email,
                'format' => 'email'
            ],

            'name',
            'address',
            'phone',
            'nurseImageUrl:url',

            //department_id
            [
                'attribute' => 'department_id',
                'label' => 'Department',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/department/view?id='.
                    $model->department_id . '">' .
                    $model->department->title . '</a>'
            ],

            'created_at:date',
            'updated_at:date'
        ],
    ])
    ?>
</div>
