<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InsuranceCompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-index">


    <p>
        <?php
        if( Yii::$app->user->identity->role === User::ROLE_ADMIN) {
            echo Html::a('Create Insurance Company', ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'company_title',
            'company_description',
//            'companyImageUrl:url',

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
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) {
                        return Yii::$app->user->identity->role  === User::ROLE_ADMIN;
                    },
                    'delete' => function ($model, $key, $index) {
                        return Yii::$app->user->identity->role  === User::ROLE_ADMIN;
                    }
                ]
            ],
        ],
    ]); ?>
</div>
