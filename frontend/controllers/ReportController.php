<?php

namespace frontend\controllers;

use backend\components\BaseController;
use Yii;
use common\models\User;
use common\models\Report;
use common\models\ReportSearch;
use common\models\Patient;
use common\models\Appointment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends BaseController
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
                        'create-report',
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_DOCTOR,
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
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReportSearch();

        if (Yii::$app->user->identity->role == User::ROLE_DOCTOR) {
            $array_buffer = Yii::$app->request->queryParams;
            $doctor = Yii::$app->user->identity->entity;
            $array_buffer['ReportSearch']['doctor_name'] = $doctor->name;
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
     * Displays a single Report model.
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
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Report();
        $model->creator_id = Yii::$app->user->identity->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash(
                'success', Yii::t('auth', 'You have successfully create report: ' . $model->title )
            );
            $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'appointmentsList' => Appointment::getSelfAppointmentsWithoutReportDropDown(Yii::$app->user->identity),
                'createFrom' => false
            ]);
        }
    }

    public function actionCreateReport($id) {
        $model = new Report();
        $model->creator_id = Yii::$app->user->identity->id;
        $model->appointment_id = $id;
        $appointmentsList[$id] = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash(
                'success', Yii::t('auth', 'You have successfully create report: '
                . $model->title .', for appointment №: ' .$id )
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
     * Updates an existing Report model.
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
                'appointmentsList' => Appointment::getSelfAppointmentsWithoutReportDropDown(Yii::$app->user->identity),
                'createFrom' => false
            ]);
        }
    }

    /**
     * Deletes an existing Report model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Yii::$app->session->addFlash('success', Yii::t('auth',
                'You have successfully deleted Report: ' .
                $model->title));
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, Report: ' .
                 $model->title . ' not deleted'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
