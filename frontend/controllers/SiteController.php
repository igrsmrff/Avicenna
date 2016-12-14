<?php
namespace frontend\controllers;

use Yii;
use backend\components\BaseController;
use common\models\Appointment;
use common\models\Invoice;
use common\models\Payment;
use common\models\Initialinspection;
use common\models\Receptionist;
use frontend\models\LoginForm;
use frontend\models\User;
use common\models\Doctor;
use common\models\Nurse;
use common\models\Patient;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\helpers\Html;
use yii\filters\VerbFilter;
use Mailgun\Mailgun;
/**
 * Site controller for start page
 */
class SiteController extends BaseController
{
    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = array_merge(
            [
                [
                    'actions' => ['login'],
                    'allow' => true,
                    'roles' => ['?'],
                ],

                [
                    'actions' => ['logout', 'index'],
                    'allow' => true,
                    'roles' => ['@'],
                ],


            ],
            $behaviors['access']['rules']
        );
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        switch (Yii::$app->user->identity->role) {
            case User::ROLE_DOCTOR:
                return $this->redirect('/appointment/calendar');
                break;
            case User::ROLE_NURSE:
                return $this->redirect('/appointment/calendar');
                break;
            case User::ROLE_PHARMACIST:
                return $this->redirect('/patient/index');
                break;
            case User::ROLE_RECEPTIONIST:
                return $this->redirect('/appointment/calendar');
                break;
            case User::ROLE_LABORATORIST:
                return $this->redirect('/patient/index');
                break;
            case User::ROLE_ACCOUNTANT:
                return $this->redirect('/invoice/index');
                break;
        }

        $doctors = count(Doctor::findAll());
        $nurses = count(Nurse::findAll());
        $patients = count(Patient::findAll());
        $appointments = count(Appointment::findAll());
        $receptionist = count(Receptionist::findAll());
        $payments = count(Payment::findAll());
        $invoices = count(Invoice::findAll());
        $initialinspection = count(Initialinspection::findAll());

        $userRole = 'Guest';
        if (!\Yii::$app->user->isGuest) {
            $userRole = Yii::$app->user->identity->role;
            return $this->render('index' , [
                'userRole' => $userRole,
                'doctors' => $doctors,
                'nurses' => $nurses,
                'patients' => $patients,
                'appointments' =>$appointments,
                'receptionist' => $receptionist,
                'payments' => $payments,
                'invoices' => $invoices,
                'initialinspection' => $initialinspection
            ]);
        } else {
            $this->redirect('/site/login');
        }
    }



    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        if (!\Yii::$app->user->isGuest) {
            $this->redirect('index');
        }

        $model = new LoginForm();
        $session = Yii::$app->session;

        if( count(Yii::$app->request->post())>0 ) {

            $model->load(Yii::$app->request->post() );

            if( $model->login() ) {
                $this->redirect('index');
            } else {
                $session->setFlash('passwordLogin', 'Sorry, but password or login is incorrect.');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->addFlash('success', Yii::t('frontend', 'Check your email for further instructions.'));

                return $this->goHome();
            } else {
                Yii::$app->session->addFlash('error', Yii::t('frontend', 'Sorry, we are unable to send reset password email. Please contact site administrator for more details.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     */
    public function actionResetPassword($token)
    {
        $model = new ResetPasswordForm(['token' => $token]);


        if (!$model->validate(['token'])) {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'authorization',
                    'Wrong password reset token provided. {0}',
                    [
                        Html::a(Yii::t('authorization', 'Resend?'), ['site/request-password-reset']),
                    ]
                )
            );
            return $this->goHome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->addFlash('success', Yii::t('frontend', 'New password was saved. You can login now.'));

            return $this->redirect(['login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Resend verification email
     *
     * @param $id string User ID
     * @return \yii\web\Response
     */
    public function actionResendVerification($id)
    {
        $user = User::findOne($id);
        if (!$user || $user->status != User::STATUS_PENDING) {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'No such user found. Please make sure you have provided correct credentials and your account is not verified yet.'
                )
            );
            return $this->redirect(['login']);
        }

        if ($user->sendVerificationEmail()) {
            Yii::$app->session->addFlash(
                'success',
                Yii::t(
                    'auth',
                    'Verification link was successfully sent to your email address. Please follow that link to proceed.'
                )
            );
        } else {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'Failed to send verification link. Please contact site administrator for more details.'
                )
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Verify email by one-time token
     *
     * @param $token string
     * @return \yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        $user = User::findByVerificationToken($token, User::STATUS_PENDING);
        
        if (!$user) {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'The verification token you have provided is invalid.'
                )
            );
            return $this->goHome();
        }
        
        if (!$user->activate()) {
            Yii::error('Failed to verify user with ID #' . $user->id . ' User errors: ' . json_encode($user->getErrors()), 'auth');
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'An error occurred during email verification. Please contact site administrator for more details.'
                )
            );
            return $this->redirect(['index']);
        }
        
        if (!Yii::$app->user->login($user)) {
            Yii::error('Failed to login user with ID #' . $user->id,'auth');
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'An error occurred during automatic login. Please try to login manually.'
                )
            );
            return $this->redirect(['auth']);
        }
        
        Yii::$app->session->addFlash(
            'success',
            Yii::t(
                'auth',
                'You have successfully verified your email address.'
            )
        );
        return $this->goHome();
    }

    private function actionSendEmail() {

        $mg = new Mailgun("key-0af3a1fbe3f4a9dabe3fc5000cb721e7");
        $domain = "sandboxdf7af0f5e80b4aada11f7a6212ef1ad9.mailgun.org";
        $mg->sendMessage(
            $domain, [
            'from'    => 'smirnoffnew@gmail.com',
            'to'      => 'smirnoffnew@gmail.com',
            'subject' => 'The PHP SDK is awesome!',
            'text'    => 'It is so simple to send a message.'
        ]);

    }
}