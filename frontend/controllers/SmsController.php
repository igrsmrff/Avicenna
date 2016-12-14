<?php

namespace frontend\controllers;

use common\models\Appointment;
use Yii;
use common\models\User;
use common\models\Sms;
use common\models\SmsSearch;
use common\custom\CustomSmsSender;
use backend\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Twilio\Rest\Client;

/**
 * SmsController implements the CRUD actions for Sms model.
 */
class SmsController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = array_merge(
            [
                [
                    'actions' => [
                        'index',
                        'view',
                        'create',
                        'update',
                        'delete',
                        'send-sms'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_RECEPTIONIST,
                    ],
                ],

                [
                    'actions' => [
                        'view',
                        'index',
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_DOCTOR,
                        User::ROLE_NURSE,
                    ],
                ],
            ],
            $behaviors['access']['rules']
        );

        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Lists all Sms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sms model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sms();
        $model->creator_id = Yii::$app->user->identity->id;
        $model->status = Sms::STATUS_SENT;

        if ( $model->load( Yii::$app->request->post() ) ) {
            $sender = new CustomSmsSender($model->appointment_id);

            if( $sender->send($model->text_message) ){
                Yii::$app->session->addFlash('success', Yii::t('auth',
                    'You successfully send SMS reminder to Patient: ' .
                    $sender->appointment->patient->name . ' for Appointment №: ' .
                    $sender->appointment->id));
                $model->message_identifier = $sender->message_identifier;
            } else {
                Yii::$app->session->addFlash('success', Yii::t('auth',
                    'Something went wrong, SMS reminder to Patient: ' .
                    $sender->appointment->patient->name . ' for Appointment №: ' .
                    $sender->appointment->id . ' not sent' ));
                $model->message_identifier = $sender->errors;
            }

            $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'appointmentsList' => Appointment::getAllAppointmentsWithoutSmsDropDown(),
                'createFrom' => false
            ]);
        }
    }


    public function actionSendSms($id)
    {
        $sender = new CustomSmsSender($id);

        if( $sender->send() ) {

            $model = new Sms();
            $model->text_message = $sender->smsBody;
            $model->status = Sms::STATUS_SENT;
            $model->appointment_id = $id;
            $model->creator_id = Yii::$app->user->identity->id;
            $model->message_identifier = $sender->message_identifier;
            $model->save();

            Yii::$app->session->addFlash('success', Yii::t('auth',
                'You successfully send SMS reminder, for Appointment № ' .
                $sender->appointment->id ));

            return $this->redirect('index');
        } else {

            $model = new Sms();
            $model->text_message = $sender->smsBody;
            $model->status = Sms::STATUS_NOT_SENT;
            $model->appointment_id = $id;
            $model->creator_id = Yii::$app->user->identity->id;
            $model->message_identifier = $sender->errors;
            $model->save();

            Yii::$app->session->addFlash('error',
                Yii::t('auth',
                    'Something went wrong, SMS reminder for Appointment №: ' .
                    $sender->appointment->id . ' not sent. Error: '. $sender->errors
                )
            );
        }

    }


    /**
     * Deletes an existing Sms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ( $model->delete()) {
            Yii::$app->session->addFlash(
                'warning',
                Yii::t(
                    'auth',
                    'You have successfully deleted sms reminder №: ' .
                    $model->id . ' for Appointment №: ' .
                    $model->appointment->id
                )
            );

        } else {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'Something went wrong, sms reminder №: ' .
                    $model->id . ' for Appointment №: ' .
                    $model->appointment->id . ' not deleted'
                )
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Sms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
