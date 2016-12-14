<?php

namespace frontend\controllers;

use backend\components\BaseController;
use Yii;
use frontend\models\SignupForm;
use common\models\Accountant;
use common\models\AccountantSearch;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountantController implements the CRUD actions for Accountant model.
 */
class AccountantController extends BaseController
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
                        User::ROLE_DOCTOR,
                        User::ROLE_NURSE,
                        User::ROLE_RECEPTIONIST,
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
     * Lists all Accountant models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountantSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Accountant model.
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
     * Creates a new Accountant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userModel = new SignupForm;
        $accountModel = new Accountant;

        if( array_key_exists('Accountant' , Yii::$app->request->post()) &&  array_key_exists('SignupForm', Yii::$app->request->post())){


            $user = Yii::$app->request->post();
            unset($user['Accountant']);
            $user['SignupForm']['role']=User::ROLE_ACCOUNTANT;
            $userModel->load($user);

            $account = Yii::$app->request->post();
            unset($account['SignupForm']);
            $accountModel->load($account);

            $accountModel->user_id_validate_flag = false;
            if( $accountModel->validate() && $userModel->validate() ) {
                $userModel->signup(false);
                $accountModel->user_id  = $userModel->getUserId();
                $accountModel->save();
                Yii::$app->session->addFlash(
                    'success',
                    Yii::t(
                        'auth',
                        'You have successfully create account: ' . $userModel->username
                    )
                );
                $this->redirect('index');
            } else {
                return $this->render('create', [
                    'userModel' => $userModel,
                    'accountModel' => $accountModel,
                ]);
            }

        } else {
            return $this->render('create', [
                'userModel' => $userModel,
                'accountModel' => $accountModel,
            ]);
        }
    }

    /**
     * Updates an existing Accountant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userForDeactivate = User::find()->where(['id' => $model->user_id])->one();

        if ( $model->load(Yii::$app->request->post()) && $model->validate() ) {
            $userForDeactivate->deactivate();
            if ( $model->save() ){
                $userForActivate = User::find()->where(['id' => $model->user_id])->one();
                $userForActivate->activate();
                Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully '));
                return $this->redirect('index');
            }
        } else {
            $available_users = User::dropDownAvailableUsers(User::ROLE_ACCOUNTANT);
            $available_users[$model->getUser()->one()->id] = $model->getUser()->one()->username;
            return $this->render('update', [
                'model' => $model,
                'available_users' => $available_users,
            ]);
        }
    }

    /**
     * Deletes an existing Accountant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Accountant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Accountant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Accountant::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
