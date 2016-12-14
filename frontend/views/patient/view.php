<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Patient;
use common\models\InsuranceCompany;

/* @var $this yii\web\View */
/* @var $model common\models\Patient */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Patients', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="patient-view">

    <p>
        <?php
        if ( Patient::abilityCreate(Yii::$app->user->identity->role) ) {
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
            'name',
            'address',
            'phone',

            //sex
            [
                'label' => 'Sex',
                'value' => Patient::SEX_ARRAY[$model->sex]

            ],

            //marital_status
            [
                'attribute' => 'marital_status',
                'value' => Patient::MARITAL_STATUS_ARRAY[$model->marital_status]
            ],

            //insurance_company_id
            [
                'attribute' => 'insurance_company_id',
                'format' => 'raw',
                'value' => '<a class = "link-underline" href="/insurancecompany/view?id='.
                    $model->insurance_company_id . '">' .
                    $model->insuranceCompany->company_title . '</a>'
            ],

            'insurance_number',
            'insurance_expiration_date:date',

            [
                'attribute' => 'birth_date',
                'label' => 'Date of Birth',
                'format' => ['date']
            ],

            'patientImageUrl:url',
            'created_at:date',
            'updated_at:date',
        ],
    ])
    ?>

</div>
