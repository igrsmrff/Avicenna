<?php
    use yii\helpers\Html;
    use yii\bootstrap\NavBar;
    use yii\bootstrap\Nav;
    use frontend\models\LoginForm;
    use common\custom\Bookmarks;

    /* @var $this \yii\web\View */
    /* @var $content string */

    //get rules for sidebar widget
    $role = 'Guest';
    $bokmarks = new Bookmarks();
    if (!\Yii::$app->user->isGuest) {
        $role = \Yii::$app->user->identity->role;
        $bokmarks = new Bookmarks($role);
    }
    $rules = $bokmarks->getRules();

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>
    <?php $this->beginPage() ?>
        <!DOCTYPE html>
        <html lang="<?= Yii::$app->language ?>">
            <head>
                <meta charset="<?= Yii::$app->charset ?>"/>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <?= Html::csrfMetaTags() ?>
                <title><?= Html::encode($this->title) ?></title>
                <?php $this->head() ?>
            </head>
            <body class="<?= \dmstr\helpers\AdminLteHelper::skinClass() ?>">
                <?php $this->beginBody() ?>
                    <div class="wrap">
                        <?php
                            if (!Yii::$app->user->isGuest) {
                                NavBar::begin([
                                    'brandLabel' => '<img src="/logo.png" class="logo-class"/><span>Avicenna</span>',
                                    'brandUrl' => Yii::$app->homeUrl,
                                    'options' => [
                                        'class' => 'navbar-custom navbar-inverse navbar-fixed-top',
                                    ],
                                ]);

                                    $menuItems = [
                                        ['label' => 'Dashboard', 'icon' => ['fa fa-desktop'], 'url' => ['site/index'], 'visible' => $rules['dashboard']],
                                        ['label' => 'Appointments Calendar', 'icon' => 'fa fa-calendar', 'url' => ['appointment/calendar'], 'visible' => $rules['calendar']],
                                        ['label' => 'My Schedule', 'icon' => 'fa fa-file-text', 'url' => ['users-schedule/my-schedule'], 'visible' => $rules['my_schedule']],
                                        ['label' => 'Departments', 'icon' => 'fa fa-sitemap', 'url' => ['department/index'], 'visible' => $rules['departments']],
                                        ['label' => 'Insurance Company', 'icon' => 'fa fa-university', 'url' => ['insurancecompany/index'], 'visible' => $rules['insurancecompany']],
                                        ['label' => 'My accountant', 'icon' => 'fa fa-user', 'url' => ['myaccount/index'], 'visible' => $rules['my_accountant'],

                                            'items' => [
                                                ['label' => 'Edit account', 'icon' => 'fa fa-user', 'url' => ['myaccount/account'],],

                                                [
                                                    'label' => Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN ? 'System Settings' : 'Edit profile',
                                                    'icon' => 'fa fa-pencil',
                                                    'url' => ['myaccount/profile'],
                                                ],

                                                ['label' => 'Change password', 'icon' => 'fa fa-key', 'url' => ['myaccount/changepassword'],],
                                            ],
                                        ],
                                        ['label' => 'My patients', 'icon' => 'fa fa-user', 'url' => ['doctor-patient/index'], 'visible' => $rules['doctors_patients']],
                                        ['label' => 'My patients', 'icon' => 'fa fa-user', 'url' => ['nurse-patient/index'], 'visible' => $rules['nurses_patients']],
                                        ['label' => 'Appointments', 'icon' => 'fa fa-bell-o', 'url' => ['appointment/index'], 'visible' => $rules['appointments']],
                                        ['label' => 'Sms', 'icon' => 'fa fa-envelope-o ', 'url' => ['sms/index'], 'visible' => $rules['sms']],
                                        ['label' => 'Patients', 'icon' => 'fa fa-user', 'url' => ['patient/index'], 'visible' => $rules['patients']],
                                        ['label' => 'Initial inspections', 'icon' => 'fa fa-stethoscope', 'url' => ['initialinspection/index'], 'visible' => $rules['initial_inspection']],
                                        ['label' => 'Reports', 'icon' => 'fa fa-briefcase', 'url' => ['report/index'], 'visible' => $rules['reports']],
                                        ['label' => 'Prescription', 'icon' => 'fa fa-check-square-o', 'url' => ['prescription/index'], 'visible' => $rules['prescription']],
                                        ['label' => 'Invoices', 'icon' => 'fa fa-book', 'url' => ['invoice/index'], 'visible' => $rules['invoices']],
                                        ['label' => 'Invoice Entries', 'icon' => 'fa fa-heartbeat', 'url' => ['invoice-entry-drop-down/index'], 'visible' => $rules['invoice_entries']],
                                        ['label' => 'Payments', 'icon' => 'fa fa-usd', 'url' => ['payment/index'], 'visible' => $rules['payments']],
                                        ['label' => 'Accounts', 'icon' => 'fa fa-newspaper-o', 'url' => ['user/index'], 'visible' => $rules['accountants']],
                                        ['label' => 'Doctors', 'icon' => 'fa fa-user-md', 'url' => ['doctor/index'], 'visible' => $rules['doctors']],
                                        ['label' => 'Nurses', 'icon' => 'fa fa-plus-square', 'url' => ['nurse/index'], 'visible' => $rules['nurses']],
                                        ['label' => 'Receptionists', 'icon' => 'fa fa-pencil-square-o', 'url' => ['receptionist/index'], 'visible' => $rules['receptionists']],
                                        ['label' => 'Pharmacists', 'icon' => 'fa fa-medkit', 'url' => ['pharmacist/index'], 'visible' => $rules['pharmacists']],
                                        ['label' => 'Laboratorists', 'icon' => 'fa fa-user-md', 'url' => ['laboratorist/index'], 'visible' => $rules['laboratorists']],
                                        ['label' => 'Accountants', 'icon' => 'fa fa-money', 'url' => ['accountant/index'], 'visible' => $rules['accountant']],

                                    ];

                                    echo Nav::widget([
                                        'options' => ['class' => 'navbar-nav'],
                                        'items' => $menuItems,
                                    ]);
                                NavBar::end();
                            }
                        ?>

                        <div <?php echo Yii::$app->user->isGuest ? 'style="margin-top:0"': false; ?> class="wrapper rr">
                            <?php
                                if (!\Yii::$app->user->isGuest) {
                                    echo $this->render('left.php', ['directoryAsset' => $directoryAsset]);
                                    echo $this->render('content.php', ['content' => $content, 'directoryAsset' => $directoryAsset]);
                                } else {
                                    echo $this->render('../site/login.php',['model'=> new LoginForm()]);
                                }
                            ?>
                        </div>
                    </div>
                <?php $this->endBody() ?>
            </body>
        </html>
    <?php $this->endPage() ?>
