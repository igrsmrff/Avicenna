<?php

/* @var $this yii\web\View */

$this->registerCssFile('../css/dashboard.css');
$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-3">
        <a href="/doctor/index">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-user-md"></i></div>
                <div class="color-black num" data-start="0" data-end="4" data-duration="1500" data-delay="0">
                    <?= $doctors ?>
                </div>
                <h3 class="color-black">Doctor</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/nurse/index">
            <div class="tile-stats tile-white-aqua">
                <div class="icon"><i class="fa fa-plus-square"></i></div>
                <div class="num color-light-blue" data-start="0" data-end="1" data-duration="1500" data-delay="0">
                    <?= $nurses ?>
                </div>
                <h3 class="color-light-blue">Nurse</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/patient/index">
            <div class="tile-stats tile-white-red">
                <div class="icon"><i class="fa fa-user"></i></div>
                <div class="num color-rose" data-start="0" data-end="2" data-duration="1500" data-delay="0">
                    <?= $patients ?>
                </div>
                <h3 class="color-rose">Patient</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/receptionist/index">
            <div class="tile-stats tile-white-blue">
                <div class="icon"><i class="fa fa-user-md"></i></div>
                <div class="color-blue num" data-start="0" data-end="1" data-duration="1500" data-delay="0">
                    <?= $receptionist ?>
                </div>
                <h3 class="color-blue">Receptionist</h3>
            </div>
        </a>
    </div>
</div>



<div class="row">

    <div class="col-sm-3">
        <a href="/appointment/index">
            <div class="tile-stats tile-white-orange">
                <div class="icon"><i class="fa fa-hospital-o"></i></div>
                <div class="color-yellow num" data-start="0" data-end="0" data-duration="1500" data-delay="0">
                    <?= $appointments ?>
                </div>
                <h3 class="color-yellow">Appointment</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/initialinspection/index">
            <div class="tile-stats tile-white-cyan">
                <div class="icon"><i class="fa fa-stethoscope"></i></div>
                <div class="color-turquoise num" data-start="0" data-end="1" data-duration="1500" data-delay="0">
                    <?= $initialinspection ?>
                </div>
                <h3 class="color-turquoise">Initialinspection</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/invoice/index">
            <div class="tile-stats tile-white-purple">
                <div class="icon"><i class="fa fa-heartbeat"></i></div>
                <div class="color-purple num" data-start="0" data-end="1" data-duration="1500" data-delay="0">
                    <?= $invoices ?>
                </div>
                <h3 class="color-purple">Invoices</h3>
            </div>
        </a>
    </div>

    <div class="col-sm-3">
        <a href="/payment/index">
            <div class="tile-stats tile-white-pink">
                <div class="icon"><i class="fa fa-usd"></i></div>
                <div class="color-fuchsia num" data-start="0" data-end="0" data-duration="1500" data-delay="0">
                    <?= $payments ?> £
                </div>
                <h3 class="color-fuchsia">Payment</h3>
            </div>
        </a>
    </div>

</div>