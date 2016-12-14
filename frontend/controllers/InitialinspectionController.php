<?php

namespace frontend\controllers;

use backend\components\BaseController;
use common\models\Appointment;
use Yii;
use common\models\User;
use common\models\Patient;
use common\models\InsuranceCompany;
use common\models\Initialinspection;
use common\models\InitialinspectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * InitialinspectionController implements the CRUD actions for Initialinspection model.
 */
class InitialinspectionController extends BaseController
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
                        'create-initialinspection'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_NURSE,
                    ],
                ],

                [
                    'actions' => ['view','index'],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_PHARMACIST,
                        User::ROLE_DOCTOR
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
     * Lists all Initialinspection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InitialinspectionSearch();
        $array_buffer = Yii::$app->request->queryParams;

        switch (Yii::$app->user->identity->role) {
            case User::ROLE_DOCTOR:
                $doctor = Yii::$app->user->identity->entity;
                $array_buffer['InitialinspectionSearch']['doctor_name'] = $doctor->name;
                $dataProvider = $searchModel->search($array_buffer);
                break;
            case User::ROLE_NURSE:
                $nurse = Yii::$app->user->identity->entity;
                $array_buffer['InitialinspectionSearch']['nurse_name'] = $nurse->name;
                $dataProvider = $searchModel->search($array_buffer);
                break;
            default:
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }

        $dataProvider->pagination->pageSize = 20;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'myIndex' => false
        ]);
    }


    /**
     * Displays a single Initialinspection model.
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
     * Creates a new Initialinspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Initialinspection;
        $model->creator_id = Yii::$app->user->identity->id;

        if( $model->load(Yii::$app->request->post()) && $model->save() )  {
                Yii::$app->session->addFlash(
                    'success', Yii::t('auth', 'You have successfully create Initial Inspection for Appointment № ' .
                    $model->id . ' for patient: ' . $model->patient->name)
                );
                $this->redirect('index');
        } else {

            return $this->render('create', [
                'model' => $model,
                'appointmentsList' => Appointment::getSelfAppointmentsWithoutInitialInspectionDropDown(Yii::$app->user->identity),
                'createFrom' => false
            ]);
        }
    }

    /**
     * Creates a new Initialinspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateInitialinspection($id)
    {
        $model = new Initialinspection;
        $model->creator_id = Yii::$app->user->identity->id;
        $model->appointment_id = $id;
        $appointmentsList[$id] = $id;

        if( $model->load(Yii::$app->request->post()) && $model->save() )  {
            Yii::$app->session->addFlash(
                'success', Yii::t('auth', 'You have successfully create Initial Inspection for Appointment № ' .
                $model->id . ' for patient: ' . $model->patient->name)
            );
            $this->redirect('/appointment/index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'appointmentsList' => $appointmentsList,
                'createFrom' => true
            ]);
        }
    }



    /**
     * Updates an existing Initialinspection model.
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
            return $this->render('update', [
                'model' => $model,
                'appointmentsList' => Appointment::getSelfAppointmentsWithoutInitialInspectionDropDown(Yii::$app->user->identity),
                'createFrom' => false
            ]);
        }
    }

    /**
     * Deletes an existing Initialinspection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->addFlash('warning', Yii::t('auth',
                'You have successfully deleted Initial Inspection № ' . $model->id .
                ' for patient: ' . $model->patient->name)
            );
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, Initial Inspection № ' .
                ' for patient: ' . $model->id . $model->patient->name . ' not deleted')
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Initialinspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Initialinspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Initialinspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
