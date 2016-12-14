<?php

namespace frontend\controllers;

use backend\components\BaseController;
use yii\filters\VerbFilter;
use common\models\Department;
use common\models\Patient;
use frontend\models\User;
use Yii;

class MyaccountController extends BaseController
{
    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['access']['rules'] = array_merge(
            [
                [
                    'actions' => ['index','account','profile','changepassword'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
            $behaviors['access']['rules']
        );
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionIndex() {
        $model = User::findOne(Yii::$app->user->identity->id);
        return $this->render('view', [
            'model' => $model,
            'entityModel' => $model->getEntity(),
            'modelFields' => self::getModelFields()
        ]);
    }


    public function actionAccount()
    {
        $model = User::findOne(Yii::$app->user->identity->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash(
                'success', Yii::t('auth', 'You have successfully updated you account.')
            );
            return $this->redirect(['account']);
        } else {
            return $this->render('account', [
                'model' => $model,
            ]);
        }
    }

    public function actionProfile() {
        $model = User::findOne(Yii::$app->user->identity->id)->getEntity();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash(
                'success',
                Yii::t('auth', 'You have successfully updated you profile.')
            );
            return $this->redirect(['index']);
        } else {

            return $this->render('profile', [
                'model' => $model,
                'modelFields' => self::getModelFields()
            ]);
        }
    }

    public function actionChangepassword() {
        $model = User::findOne(Yii::$app->user->identity->id);
        $model->changePassword = true;
        if($model->load(Yii::$app->request->post())){
            if($model->validate()){
                try{
                    $model->setPassword($_POST['User']['newpass']);
                    $model->changePasswordFlag=false;
                    if($model->save()){
                        Yii::$app->getSession()->setFlash(
                            'success','Password changed'
                        );
                        return $this->redirect(['changepassword']);
                    }else{
                        Yii::$app->getSession()->setFlash(
                            'error','Password not changed'
                        );
                        return $this->redirect(['changepassword']);
                    }
                }catch(Exception $e){
                    Yii::$app->getSession()->setFlash(
                        'error',"{$e->getMessage()}"
                    );
                    return $this->render('../user/changepassword',[
                        'breadcrumbsTitle'=>'My account',
                        'model'=>$model
                    ]);
                }
            }else{

                return $this->render('../user/changepassword',[
                    'breadcrumbsTitle'=>'My account',
                    'model'=>$model
                ]);
            }

        }else{

            return $this->render('../user/changepassword',[
                'breadcrumbsTitle'=>'My account',
                'model'=>$model
            ]);
        }
    }

    public static function getModelFields() {
        switch ( Yii::$app->user->identity->role ) {
            case User::ROLE_ADMIN:
                return [
                    ['title' => 'system_name', 'type' => 'textInput'],
                    ['title' => 'system_title', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                    ['title' => 'paypal_email', 'type' => 'textInput'],
                    ['title' => 'currency', 'type' => 'textInput'],
                    ['title' => 'vat_percentage', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'phone_country_code', 'type' => 'textInput'],
                    ['title' => 'system_email', 'type' => 'textInput'],
                    ['title' => 'language', 'type' => 'textInput'],
                ];
                break;

            case User::ROLE_DOCTOR:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                    ['title' => 'profile', 'type' => 'textInput'],
                    ['title' => 'department_id', 'type' => 'dropDownList', 'list' =>  Department::getDepartmentsDropDown(), 'label'=>'Department']
                ];
                break;

            case User::ROLE_NURSE:
                return  [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                    ['title' => 'department_id', 'type' => 'dropDownList', 'list' =>  Department::getDepartmentsDropDown(), 'label'=>'Department']
                ];
                break;

            case User::ROLE_PATIENT:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                    ['title' => 'birth_date', 'type' => 'textInput'],
                    ['title' => 'sex', 'type' => 'dropDownList', 'list' => Patient::SEX_ARRAY, 'label'=>'Sex']
                ];
                break;

            case User::ROLE_ACCOUNTANT:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                ];
                break;

            case User::ROLE_RECEPTIONIST:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                ];
                break;

            case User::ROLE_LABORATORIST:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                ];
                break;

            case User::ROLE_PHARMACIST:
                return [
                    ['title' => 'name', 'type' => 'textInput'],
                    ['title' => 'phone', 'type' => 'textInput'],
                    ['title' => 'address', 'type' => 'textInput'],
                ];
                break;

            default:
                return false;
        }
    }
}


