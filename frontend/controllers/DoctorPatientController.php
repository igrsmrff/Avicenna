<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\DoctorPatientPivot;
use common\models\DoctorPatientSerach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DoctorPatientController implements the CRUD actions for DoctorPatientPivot model.
 */
class DoctorPatientController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all DoctorPatientPivot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DoctorPatientSerach();
        $array_buffer = Yii::$app->request->queryParams;
        $doctor = Yii::$app->user->identity->entity;
        $array_buffer['DoctorPatientSerach']['doctor_id'] = $doctor->id;
        $dataProvider = $searchModel->search($array_buffer);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Deletes an existing DoctorPatientPivot model.
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
                    'You have successfully deleted patient form my patients list: ' .   $model->patient->name
                )
            );

        } else {
            Yii::$app->session->addFlash(
                'error',
                Yii::t(
                    'auth',
                    'Something went wrong, patient ' .   $model->patient->name . ' not deleted'
                )
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the DoctorPatientPivot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DoctorPatientPivot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DoctorPatientPivot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
