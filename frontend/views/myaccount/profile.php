<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DoctorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edit profile:  '. Yii::$app->user->identity->username;
$this->params['breadcrumbs'][] = ['label' => 'My account', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit profile';
?>

<div class="patient-update">
    <?= $this->render('_form', [
        'accountModel' => false,
        'entityModel' => $model,
        'modelFields' => $modelFields
    ]) ?>
</div>