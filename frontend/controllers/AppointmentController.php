<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\models\User;
use common\models\Doctor;
use common\models\Nurse;
use common\models\Patient;
use common\models\Appointment;
use common\models\AppointmentSearch;
use common\models\DoctorPatientPivot;
use common\models\NursePatientPivot;
use backend\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2fullcalendar\models\Event;
/**
 * AppointmentController implements the CRUD actions for Appointment model.
 */
class AppointmentController extends BaseController
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
                        'calendar',
                        'create-appointment'
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
                        'calendar',
                        'create-appointment-patient'
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

    public function actionIndex()
    {
        $searchModel = new AppointmentSearch();
        $array_buffer = Yii::$app->request->queryParams;

        switch (Yii::$app->user->identity->role) {
            case User::ROLE_DOCTOR:
                $view = 'index-doctor';
                $doctor = Yii::$app->user->identity->entity;
                $array_buffer['AppointmentSearch']['doctor_id'] = $doctor->name;
                $dataProvider = $searchModel->search($array_buffer);
                break;
            case User::ROLE_NURSE:
                $view = 'index-nurse';
                $nurse = Yii::$app->user->identity->entity;;
                $array_buffer['AppointmentSearch']['nurse_id'] = $nurse->name;
                $dataProvider = $searchModel->search($array_buffer);
                break;
            default:
                $view = 'index';
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        $dataProvider->pagination->pageSize = 20;
        return $this->render($view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'myIndex' => false,
            'events' => $this->events()
        ]);
    }

    public function actionCalendar()
    {
        return $this->render('calendar', [
            'events' => $this->events()
        ]);
    }

    /**
     * Displays a single Appointment model.
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
     * Creates a new Appointment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Appointment();
        $model->creator_id = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            DoctorPatientPivot::createNewRecordIfNotExist($model->doctor_id, $model->patient_id);
            NursePatientPivot::createNewRecordIfNotExist($model->nurse_id, $model->patient_id);
            Yii::$app->session->addFlash( 'success', Yii::t('auth', 'You have successfully create Appointment №:'
                . $model->id .' for patient: ' . Patient::findOne($model->patient_id)->name )
            );
            $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'patientsList' => Patient::getPatientsListDropDown(),
                'doctorsList' => Doctor::getDoctorsListDropDown(),
                'nursesList' => Nurse::getNursesListDropDown(),
                'statusesList' => Appointment::STATUSES_ARRAY,
                'createFromPatient' => false,
                'createFromDoctor' => false,
                'createFromNurse' => false
            ]);
        }
    }

    public function actionCreateAppointmentPatient($patient_id) {

        $model = new Appointment();
        $model->creator_id = Yii::$app->user->identity->id;
        $model->doctor_id = Yii::$app->user->identity->entity->id;
        $model->patient_id = $patient_id;

        $patient = Patient::findOne($patient_id);
        $patientsList[$patient->id] = $patient->name;
        $doctorsList[Yii::$app->user->identity->entity->id] = Yii::$app->user->identity->entity->name;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            DoctorPatientPivot::createNewRecordIfNotExist($model->doctor_id, $model->patient_id);
            NursePatientPivot::createNewRecordIfNotExist($model->nurse_id, $model->patient_id);
            Yii::$app->session->addFlash( 'success', Yii::t('auth', 'You have successfully create Appointment №:' .
                $model->id .', for patient: ' . $model->patient->name ));
            $this->redirect('/appointment/index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'patientsList' => $patientsList,
                'doctorsList' => $doctorsList,
                'nursesList' => Nurse::getNursesListDropDown(),
                'statusesList' => Appointment::STATUSES_ARRAY,
                'createFromPatient' => true,
                'createFromDoctor' => true,
                'createFromNurse' => false
            ]);
        }
    }

    public function actionCreateAppointment($id, $from=false)
    {
        $model = new Appointment();
        $doctorsList = [];
        $nursesList = [];
        $patientsList = [];
        $createFromPatient = false;
        $createFromDoctor = false;
        $createFromNurse = false;
        $model->creator_id = Yii::$app->user->identity->id;

        if($from==User::$roles[User::ROLE_DOCTOR]) {
            $createFromDoctor = true;
            $createFromPatient = false;
            $createFromNurse = false;
            $doctor = Doctor::findOne($id);
            $model->doctor_id = $doctor->id;
            $doctorsList[$doctor->id] = $doctor->name;
            $nursesList = Nurse::getNursesListDropDown();
            $patientsList = Patient::getPatientsListDropDown();

        } elseif ($from==User::$roles[User::ROLE_NURSE]) {
            $createFromNurse = true;
            $createFromPatient = false;
            $createFromDoctor = false;
            $nurse =  Nurse::findOne($id);
            $model->nurse_id = $nurse->id;
            $nursesList[$nurse->id] = $nurse->name;
            $doctorsList = Doctor::getDoctorsListDropDown();
            $patientsList = Patient::getPatientsListDropDown();

        } elseif ($from==User::$roles[User::ROLE_PATIENT]) {
            $createFromPatient = true;
            $createFromDoctor = false;
            $createFromNurse = false;
            $patient = Patient::findOne($id);
            $model->patient_id = $patient->id;
            $patientsList[$id] = $patient->name;
            $doctorsList = Doctor::getDoctorsListDropDown();
            $nursesList = Nurse::getNursesListDropDown();
        }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            DoctorPatientPivot::createNewRecordIfNotExist($model->doctor_id, $model->patient_id);
            NursePatientPivot::createNewRecordIfNotExist($model->nurse_id, $model->patient_id);
            Yii::$app->session->addFlash( 'success', Yii::t('auth', 'You have successfully create Appointment №:' .
                $model->id .', for patient: ' .
                $model->patient->name . ' with doctor: ' . $model->doctor->name));
            $this->redirect('/appointment/index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'patientsList' => $patientsList,
                'doctorsList' => $doctorsList,
                'nursesList' => $nursesList,
                'statusesList' => Appointment::STATUSES_ARRAY,
                'createFromPatient' => $createFromPatient,
                'createFromDoctor' => $createFromDoctor,
                'createFromNurse' => $createFromNurse
            ]);
        }
    }

    /**
     * Updates an existing Appointment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            DoctorPatientPivot::createNewRecordIfNotExist($model->doctor_id, $model->patient_id);
            Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully '));
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'patientsList' => Patient::getPatientsListDropDown(),
                'doctorsList' => Doctor::getDoctorsListDropDown(),
                'nursesList' => Nurse::getNursesListDropDown(),
                'statusesList' => Appointment::STATUSES_ARRAY,
                'createFromPatient' => false,
                'createFromDoctor' => false,
                'createFromNurse' => false
            ]);
        }
    }

    /**
     * Deletes an existing Appointment model.
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
                    'You have successfully deleted Appointment №: ' .   $model->id
                )
            );

        } else {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'Something went wrong, Appointment №: ' .   $model->id . ' not deleted'
                )
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Appointment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Appointment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appointment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function events() {

        $events = [];

        switch (Yii::$app->user->identity->role) {
            case User::ROLE_DOCTOR:
                $doctor = Yii::$app->user->identity->entity;
                $public_appointments = false;
                $appointments = $doctor->appointments;
                break;
            case User::ROLE_NURSE:
                $nurse = Yii::$app->user->identity->entity;
                $public_appointments = false;
                $appointments = $nurse->appointments;
                break;
            case User::ROLE_RECEPTIONIST:
                $receptionist = Yii::$app->user->identity->entity;
                $public_appointments = true;
                $appointments = Appointment::find()->all();
                break;
            case User::ROLE_ADMIN:
                $admin = Yii::$app->user->identity->entity;
                $public_appointments = true;
                $appointments = Appointment::find()->all();
                break;
            default:
                $appointments = [];
                $public_appointments = false;
        }

        foreach ($appointments as $appointment) {
            $Event = new Event();
            $Event->id = $appointment->id;
            if ($public_appointments)
                $Event->title = $appointment->time . ' ' .
                    $appointment->patient->name . ' ' .
                    $appointment->doctor->name;
            else
                $Event->title = $appointment->time . ' Meeting with ' . $appointment->patient->name;
            $Event->start = date('Y-m-d\TH:i:s\Z',strtotime($appointment->date . $appointment->time));
            $Event->allDay = true;

            if($appointment->status == Appointment::STATUS_ACTIVE) {
                $Event->className ='calendar-link appointment-active';
            } else if ($appointment->status == Appointment::STATUS_INACTIVE) {
                $Event->className ='calendar-link appointment-inactive';
            } else if ($appointment->status == Appointment::STATUS_MISSING) {
                $Event->className ='calendar-link appointment-missing';
            }

            $Event->url ='/appointment/view?id=' . $appointment->id;
            $events[] = $Event;
        }
        return $events;
    }
}
