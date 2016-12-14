<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\InsuranceCompany */

$this->title = 'Create Insurance Company';
$this->params['breadcrumbs'][] = ['label' => 'Insurance Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-company-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
