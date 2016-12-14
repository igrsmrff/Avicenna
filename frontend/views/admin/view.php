<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-view">

    <p>
        <?php
        if (Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary marginL']);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'system_name',
            'system_title',
            'name',
            'address',
            'paypal_email:email',
            'currency',
            'vat_percentage',
            'phone',
            'system_email:ntext',
            'adminImageUrl:url',


            [
                'attribute' => 'user_id',
                'label' => 'Account user name',
                'value' => $model->user->username
            ],

            'user_id',
            'language',

            [
                'attribute' => 'updated_at',
                'label' => 'Last modified',
                'encodeLabel' => false,
                'format' => ['datetime']
            ],

            [
                'attribute' => 'created_at',
                'label' => 'Registered',
                'encodeLabel' => false,
                'format' => ['datetime']
            ]
        ],
    ]) ?>

</div>
