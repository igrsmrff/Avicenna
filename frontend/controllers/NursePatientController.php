<?php

namespace frontend\controllers;

use Yii;
use common\models\NursePatientPivot;
use common\models\NursePatientSerach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NursePatientController implements the CRUD actions for NursePatientPivot model.
 */
class NursePatientController extends Controller
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
     * Lists all NursePatientPivot models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NursePatientSerach();
        $array_buffer = Yii::$app->request->queryParams;
        $nurse = Yii::$app->user->identity->entity;
        $array_buffer['NursePatientSerach']['nurse_id'] = $nurse->id;
        $dataProvider = $searchModel->search($array_buffer);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Deletes an existing NursePatientPivot model.
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
     * Finds the NursePatientPivot model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NursePatientPivot the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NursePatientPivot::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
