<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pharmacist */

$this->title = 'Create Pharmacist';
$this->params['breadcrumbs'][] = ['label' => 'Pharmacists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pharmacist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
