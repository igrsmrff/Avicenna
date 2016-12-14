<?php

namespace frontend\controllers;

use Mailgun\Mailgun;
use backend\components\BaseController;
use Yii;
use frontend\models\User;
use common\models\InsuranceCompany;
use common\models\Patient;
use common\models\PatientSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Twilio\Rest\Client;

/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends BaseController
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
                        'send-sms',
                        'add-task'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_RECEPTIONIST
                    ],
                ],

                [
                    'actions' => ['view','index'],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_DOCTOR,
                        User::ROLE_NURSE,
                        User::ROLE_LABORATORIST,
                        User::ROLE_RECEPTIONIST,
                        User::ROLE_PHARMACIST,
                        User::ROLE_ACCOUNTANT
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
     * Lists all Patient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PatientSearch();

        if (Yii::$app->user->identity->role == User::ROLE_NURSE) {
            $array_buffer = Yii::$app->request->queryParams;
            $nurse = Yii::$app->user->identity->entity;
            $array_buffer['PatientSearch']['nurse_name'] = $nurse->name;
            $dataProvider = $searchModel->search($array_buffer);
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        $dataProvider->pagination->pageSize = 20;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Patient model.
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
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Patient();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success',
                Yii::t('auth', 'You have successfully create patient: ' . $model->name)
            );
            $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'sexListDropDown' =>  Patient::SEX_ARRAY,
                'insuranceCompanyListDropDown' => InsuranceCompany::getInsuranceCompanyListDropDown(),
                'maritalStatusListDropDown' => Patient::MARITAL_STATUS_ARRAY
            ]);
        }
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully '));
            return $this->redirect(['index']);
        } else {
//            $model->birth_date = Yii::$app->formatter->asDate($model->birth_date, "y-m-d");
            return $this->render('update', [
                'model' => $model,
                'sexListDropDown' =>  Patient::SEX_ARRAY,
                'insuranceCompanyListDropDown' => InsuranceCompany::getInsuranceCompanyListDropDown(),
                'maritalStatusListDropDown' => Patient::MARITAL_STATUS_ARRAY
            ]);
        }
    }

    /**
     * Deletes an existing Patient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->addFlash('warning', Yii::t('auth',
                'You have successfully deleted patient: ' . $model->name)
            );
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, patient: ' . $model->name . 'not deleted')
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Patient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Patient::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionSendSms()
    {
        $sid = "AC42341f1e662acf8dbcf865480da73b1f"; // Your Account SID from www.twilio.com/console
        $token = "6a9279217a6e259b9d71c1bc1569137f"; // Your Auth Token from www.twilio.com/console
        $client = new  Client($sid, $token);
        $message = $client->messages->create(
            '+380938725231',
            [
                'from' => '+19784514056 ', // From a valid Twilio number
                'body' => 'Zarabotalo ura! dateSent=> 2016-11-23 ',
            ]
        );


        try {
        echo $message->sid; // Twilio's identifier for the new message
        } catch (Services_Twilio_RestException $e) {
            echo $e->getMessage(); // A message describing the REST error
        }
    }

    public function actionAddTask() {

    }




//        $mailer = new Mailer;
//
//        $mailer
//            ->setFrom('avicennaservice@gmail.com')
//            ->setTo('smirnoffnew@mail.com')
//            ->setSubject('subject')
//            ->send();
//
//

//        $message =
//            \Yii::$app->mailerr->compose()
//            ->setSubject('test subject')
//            ->setFrom('avicennaservice@gmail.com')
//            ->setHtmlBody('test body')
//            ->setTo('smirnoffnew@mail.ru');
//
//        \Yii::$app->mailerr->send($message);


//
//# Instantiate the client.
//        $mgClient = new Mailgun('key-0af3a1fbe3f4a9dabe3fc5000cb721e7');
//        $domain = "avicenna.wlab.tech";
//
//# Make the call to the client.
//        $result = $mgClient->sendMessage("$domain",
//            array('from'    => 'avicennaservice@gmail.com',
//                'to'      => 'avicennaservice@mail.ru',
//                'subject' => 'Hello Avicenna',
//                'text'    => 'Mail text'));

//    }
}
