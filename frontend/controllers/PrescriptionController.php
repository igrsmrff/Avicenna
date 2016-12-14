<?php

namespace frontend\controllers;

use backend\components\BaseController;
use Yii;
use common\models\User;
use common\models\Prescription;
use common\models\PrescriptionSearch;
use common\models\Report;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * PrescriptionController implements the CRUD actions for Prescription model.
 */
class PrescriptionController extends BaseController
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
                        'create-prescription',
                        'update',
                        'delete'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_DOCTOR,
                    ],
                ]
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
     * Lists all Prescription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PrescriptionSearch();
        if (Yii::$app->user->identity->role == User::ROLE_DOCTOR) {
            $array_buffer = Yii::$app->request->queryParams;
            $doctor = Yii::$app->user->identity->entity;
            $array_buffer['PrescriptionSearch']['doctor_id'] = $doctor->id;
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
     * Displays a single Prescription model.
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
     * Creates a new Prescription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Prescription();
        $model->creator_id = Yii::$app->user->identity->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success',
                Yii::t('auth', 'You have successfully create prescription №' .
                    $model->id.' for report: ' .
                    $model->report->title ));
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'reportList' => Report::getSelfReportsWithoutPrescriptionDropDown(Yii::$app->user->identity),
                'createFrom' => false
            ]);
        }
    }

    /**
     * Creates a new Prescription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePrescription($id)
    {
        $model = new Prescription();
        $model->creator_id = Yii::$app->user->identity->id;
        $model->report_id = $id;
        $reportTitle = Report::findOne($id)->title;
        $reportList[$id] = $reportTitle;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success',
                Yii::t('auth', 'You have successfully create prescription №' .
                    $model->id . ' for report: ' .
                    $reportTitle )) ;
            return $this->redirect('/report/index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'reportList' =>  Report::getSelfReportsWithoutPrescriptionDropDown(Yii::$app->user->identity),
                'createFrom' => true
            ]);
        }
    }

    /**
     * Updates an existing Prescription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $reportList = Report::getSelfReportsWithoutPrescriptionDropDown(Yii::$app->user->identity);
        $reportList[$model->report_id] = $model->report->title;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
                'reportList' =>  $reportList,
                'createFrom' => false
            ]);
        }
    }

    /**
     * Deletes an existing Prescription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
        Yii::$app->session->addFlash('success', Yii::t('auth',
            'You have successfully deleted Prescription №' . $model->id ));
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, Prescription №' . $model->id . ' not deleted'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Prescription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Prescription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prescription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
