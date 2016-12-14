<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Laboratorist */

$this->title = 'Create Laboratorist';
$this->params['breadcrumbs'][] = ['label' => 'Laboratorists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="laboratorist-create">
    <?= $this->render('_form', [
        'model' => $laboModel,
        'userModel' => $userModel,
        'available_users' => false
    ]) ?>
</div>
