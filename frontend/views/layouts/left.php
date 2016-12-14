<aside class="main-sidebar">
    <section class="sidebar">

        <header class="sidebar-brand-logo" style="">
            <div class="sidebar-brand-logo-div" style="">
                <a href="/">
                    <img src="/logo.png" class="logo-sidebar-img">
                </a>
                <span><?= Yii::$app->name; ?></span>
            </div>
        </header>

        <div class="sidebar-user-info">
            <div class="sui-normal">
                <a href="/myaccount/index" class="user-link">
                    <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image" />
                    <span>Welcome,</span>
                    <strong style=""><?= \Yii::$app->user->identity->username ?> </strong>
                </a>
            </div>
        </div>

        <!--get rules for sidebar widget-->
        <?php
            use common\custom\Bookmarks;
            $role = 'Guest';
            $bokmarks = new Bookmarks();
            if (!\Yii::$app->user->isGuest) {
                $role = \Yii::$app->user->identity->role;
                $bokmarks = new Bookmarks($role);
            }
            $rules = $bokmarks->getRules();
        ?>


        <?php
            if (!\Yii::$app->user->isGuest) {
                echo dmstr\widgets\Menu::widget(
                    [
                        'options' => ['class' => 'sidebar-menu'],
                        'items' => [
                            ['label' => 'Dashboard', 'icon' => 'fa fa-desktop', 'url' => ['site/index'], 'visible' => $rules['dashboard']],
                            ['label' => 'Appointments Calendar', 'icon' => 'fa fa-calendar', 'url' => ['appointment/calendar'], 'visible' => $rules['calendar']],
                            ['label' => 'My Schedule', 'icon' => 'fa fa-file-text', 'url' => ['users-schedule/my-schedule'], 'visible' => $rules['my_schedule']],

                            ['label' => 'Departments', 'icon' => 'fa fa-sitemap', 'url' => ['department/index'], 'visible' => $rules['departments']],
                            ['label' => 'Insurance Company', 'icon' => 'fa fa-university', 'url' => ['insurancecompany/index'], 'visible' => $rules['insurancecompany']],

                            ['label' => 'My accountant', 'icon' => 'fa fa-user', 'url' => ['myaccount/index'],  'visible' => $rules['my_accountant'],

                                'items' => [
                                    ['label' => 'Edit account', 'icon' => 'fa fa-user', 'url' => ['myaccount/account'],],

                                    [
                                        'label' => Yii::$app->user->identity->role == \common\models\User::ROLE_ADMIN ? 'System Settings':'Edit profile',
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
                            ['label' => 'Doctors', 'icon' => 'fa fa-user-md', 'url' => ['doctor/index'], 'visible' => $rules['doctors'] ],
                            ['label' => 'Nurses', 'icon' => 'fa fa-plus-square', 'url' => ['nurse/index'], 'visible' => $rules['nurses']],
                            ['label' => 'Receptionists', 'icon' => 'fa fa-pencil-square-o', 'url' => ['receptionist/index'], 'visible' => $rules['receptionists']],
                            ['label' => 'Pharmacists', 'icon' => 'fa fa-medkit', 'url' => ['pharmacist/index'], 'visible' => $rules['pharmacists']],
                            ['label' => 'Laboratorists', 'icon' => 'fa fa-user-md', 'url' => ['laboratorist/index'], 'visible' => $rules['laboratorists']],
                            ['label' => 'Accountants', 'icon' => 'fa fa-money', 'url' => ['accountant/index'], 'visible' => $rules['accountant']],


//                            ['label' => 'Notices', 'icon' => 'fa fa-file-text-o', 'url' => ['notices/index'], 'visible' => $rules['notices']],
//                            ['label' => 'Messages', 'icon' => 'fa fa-envelope', 'url' => ['message/index'], 'visible' => $rules['messages']],
                        ],
                    ]
                );
            }
        ?>

    </section>
</aside>



