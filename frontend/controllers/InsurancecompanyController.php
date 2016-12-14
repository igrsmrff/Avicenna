<?php

namespace frontend\controllers;

use backend\components\BaseController;
use Yii;
use common\models\User;
use common\models\InsuranceCompany;
use common\models\InsuranceCompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * InsurancecompanyController implements the CRUD actions for InsuranceCompany model.
 */
class InsurancecompanyController extends BaseController
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
                    'actions' => ['index','view','create','update','delete'],
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN],
                ],

                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_NURSE,
                        User::ROLE_DOCTOR,
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
     * Lists all InsuranceCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InsuranceCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InsuranceCompany model.
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
     * Creates a new InsuranceCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InsuranceCompany();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success',
                Yii::t('auth', 'You have successfully create insurance company: ' . $model->company_title)
            );
            $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing InsuranceCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing InsuranceCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (  $model->delete() ) {
            Yii::$app->session->addFlash(
                'warning', Yii::t('auth', 'You have successfully deleted insurance company : ' .   $model->company_title)
            );
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, insurance company: ' . $model->name . 'not deleted')
            );
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the InsuranceCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InsuranceCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InsuranceCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
