<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use common\models\Appointment;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AppointmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calendar';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="appointment-index">

    <!--https://github.com/philippfrenzel/yii2fullcalendar-->
    <div class="custom-fullcalendar-container">
        <?= yii2fullcalendar\yii2fullcalendar::widget([
            'loading' => 'Anicenna me events...',
            'header' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'month,basicWeek,basicDay'
            ],
            'events' => $events,
            'options' => [
                'language' => 'en'
            ],
        ]);
        ?>
    </div>

</div>
