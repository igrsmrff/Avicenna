<?php

namespace frontend\controllers;

use backend\components\BaseController;
use frontend\models\SignupForm;
use Yii;
use frontend\models\User;
use common\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
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
                        'change-password',
                        'change-status'
                    ],
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm;
        $roles = User::$roles;
        unset($roles[20]);

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->addFlash(
                'success',
                Yii::t('auth', 'You have successfully registered new user - '.$model->username .',  with role - '. User::$roles[$model->role])
            );
            return $this->redirect(['index']);
        } else {
            $model->isNewRecord = true;
            $model->role = 0;
            return $this->render('create', [
                'model' => $model,
                'roles' => $roles
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $roles = User::$roles;
        unset($roles[20]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'roles' => $roles
            ]);
        }
    }

    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);

        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                try{
                    $model->setPassword($_POST['User']['newpass']);
                    $model->changePasswordFlag=false;
                    if($model->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password for account: ' . $model->username . ' successfully changed'
                        );
                        return $this->redirect(['index']);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password for account: ' . $model->username . 'not changed'
                        );
                        return $this->redirect(['index']);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('changepassword',[
                        'breadcrumbsTitle'=>'Accounts',
                        'model'=>$model
                    ]);
                }
            }else{
                return $this->render('changepassword',[
                    'breadcrumbsTitle'=>'Accounts',
                    'model'=>$model
                ]);
            }

        }else{
            return $this->render('changepassword',[
                'breadcrumbsTitle'=>'Accounts',
                'model'=>$model
            ]);
        }
    }

    public function actionChangeStatus($id) {

        $editableUser = User::findOne($id);
        $editableUser->changeStatus();

        if( $editableUser->save() ) {
            Yii::$app->session->addFlash(
                'success',
                Yii::t( 'auth', 'You have successfully changed status to: ' .
                    User::$statuses[$editableUser->status] . ' for account: ' .
                    $editableUser->username)
            );
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, status for account: ' . $editableUser->username . ' not change.' ));
        }
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->addFlash(
            'success',
            Yii::t(
                'auth',
                'You have successfully deleted account: ' .   $model->username
            )
        );
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
