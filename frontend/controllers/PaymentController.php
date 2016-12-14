<?php

namespace frontend\controllers;

use backend\components\BaseController;
use Yii;
use common\models\User;
use common\models\Payment;
use common\models\PaymentSearch;
use common\models\Invoice;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends BaseController
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
                        'create-payment',
                        'update',
                        'delete'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_ACCOUNTANT
                    ],
                ],

                [
                    'actions' => ['index','view'],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_DOCTOR,
                        User::ROLE_NURSE
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
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 20;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(Invoice::setStatus($model->invoice_id, Invoice::STATUS_PAID) ) {
                Yii::$app->session->addFlash(
                    'success', Yii::t('auth', 'You have successfully payed Invoice № '
                    . $model->invoice->invoice_number . ' by Payment № '
                    . $model->id)
                );
            } else {
                Yii::$app->session->addFlash(
                    'error',
                    Yii::t('auth', 'Sorry, but something went wrong, Invoice №'.
                        $model->invoice->invoice_number  . ' not payed')
                );
            }

            $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
                'invoicesList' => Invoice::getSelfInvoicesWithoutPaymentDropDown(Yii::$app->user->identity),
                'paymentMethodList' => Payment::PAYMENT_METHODS_ARRAY,
                'createFrom' => false
            ]);
        }
    }

    public function actionCreatePayment($id) {

        $model = new Payment();
        $model->invoice_id = $id;
        $invoice = Invoice::findOne($id);
        $invoicesList[$id] = 'Invoice № ' .
            $invoice->invoice_number . ',  Total Invoice Amount: ' .
            $invoice->total_invoice_amount . ' $';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(Invoice::setStatus($model->invoice_id, Invoice::STATUS_PAID) ) {
                Yii::$app->session->addFlash('success', Yii::t('auth', 'You have successfully create Payment № ' .
                    $model->id .', for Invoice: '.
                    $model->invoice->invoice_number));
            } else {
                Yii::$app->session->addFlash('error', Yii::t('auth', 'Sorry, but something went wrong, Invoice № '.
                    $model->invoice->invoice_number . ' not payed: ')
                );
            }
            $this->redirect('/invoice/index');

        } else {
            return $this->render('create', [
                'model' => $model,
                'invoicesList' => $invoicesList,
                'paymentMethodList' => Payment::PAYMENT_METHODS_ARRAY,
                'createFrom' => true
            ]);
        }
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_invoice_id = $model->invoice_id;

        $invoicesList = Invoice::getSelfInvoicesWithoutPaymentDropDown(Yii::$app->user->identity);
        $invoicesList[$model->invoice_id] = $model->invoice->invoice_number;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully '));

            if (Invoice::setStatus($old_invoice_id, Invoice::STATUS_NOT_PAID) &&
                Invoice::setStatus($model->invoice_id, Invoice::STATUS_PAID) &&
                $old_invoice_id!=$model->invoice_id) {
                Yii::$app->session->addFlash('success',
                    Yii::t(
                        'auth', 'You have successfully payed another Invoice № ' .
                        $model->invoice->invoice_number . ", and all changes saved")
                );
                Yii::$app->session->addFlash(
                    'success', Yii::t('auth', 'You have successfully deactivate Invoice № ' .
                    Invoice::findOne($old_invoice_id)->invoice_number )
                );
            }
           ;
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'paymentMethodList' => Payment::PAYMENT_METHODS_ARRAY,
                'invoicesList' => $invoicesList,
                'createFrom' => false
            ]);
        }
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if($model->delete() && Invoice::setStatus($model->invoice_id, Invoice::STATUS_NOT_PAID)) {
            Yii::$app->session->addFlash(
                'warning', Yii::t('auth', 'You have successfully deleted Payment № ' .
                $model->id . ' and successfully deactivate Invoice № ' .
                $model->invoice->invoice_number )
            );
        } else {
            Yii::$app->session->addFlash(
                'warning', Yii::t('auth', 'Sorry, but something went wrong, Payment № ' .
                $model->id . ' not deleted, and Invoice № '.
                $model->invoice->invoice_number . ' not deactivate for this payment: ')
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
