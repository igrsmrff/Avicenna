<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerCssFile('@web/css/login.css');
$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="parent-section">


    <div class="my-login-header">
        <div class="vertical-container">
            <div class="my-login-content">
                <a href="/" class="logo">
                    <img class="login-img" src="/logo.png" >
                </a>
                <h1 style="color:#cacaca; font-weight:100;">Avicenna</h1>
            </div>
        </div>
    </div>

    <div class="triangle"></div>

        <div class="site-login text-center">
            <div class="row text-center">

                <h4 style="color:red; margin-top: -20px;">

                    <?php $session = Yii::$app->session;
                    if( $session->getFlash('passwordLogin') ) {
                        echo $session->getFlash('passwordLogin');
                    }
                    ?>
                </h4>

                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => '/site/login']); ?>

                    <div class="login-input-paren">
                        <?= $form->field($model, 'login')->textInput([
                            'class' => 'form-control custom-input',
                            'placeholder' => 'login'
                        ]) ?>
                    </div>

                    <div class="div-separator"></div>

                    <div class="login-input-paren">
                        <?= $form->field($model, 'password')->passwordInput()->textInput([
                            'type' => 'password',
                            'class' => 'custom-input form-control',
                            'placeholder' => 'password'
                        ]) ?>
                    </div>


                    <div class="form-group parent-btn-login-submit">
                        <?= Html::submitButton('Login <i class="fa fa-sign-in" aria-hidden="true"></i>', ['class' => 'btn btn-login-submit', 'name' => 'login-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

            </div>
        </div>

</div>


