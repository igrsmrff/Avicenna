<?php

namespace frontend\controllers;

use backend\components\BaseController;
use common\models\InvoiceEntryDropDown;
use Yii;
use common\models\Admin;
use common\models\User;
use common\models\Patient;
use common\models\Appointment;
use common\models\Invoice;
use common\models\InvoiceSearch;
use common\models\InvoiceEntriesAmount;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends BaseController
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
                        'create-invoice',
                        'print-invoice'
                    ],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_ADMIN,
                        User::ROLE_ACCOUNTANT,
                        User::ROLE_DOCTOR
                    ],
                ],

                [
                    'actions' => ['view'],
                    'allow' => true,
                    'roles' => [
                        User::ROLE_NURSE,
                        User::ROLE_RECEPTIONIST
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
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        if (Yii::$app->user->identity->role == User::ROLE_DOCTOR) {
            $array_buffer = Yii::$app->request->queryParams;
            $doctor = Yii::$app->user->identity->entity;
            $array_buffer['InvoiceSearch']['doctor_id'] = $doctor->id;
            $dataProvider = $searchModel->search($array_buffer);
        } else {
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
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'invoiceEntriesAmount' => $model->invoiceEntriesAmount
        ]);
    }

    public function actionPrintInvoice($id)
    {
        $invoice =  $this->findModel($id);
        return $this->render('paper', [
            'invoice' => $invoice,
            'system' => Admin::findOne(Admin::ADMIN_ID),
            'invoiceEntriesAmount' => $invoice->invoiceEntriesAmount
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
        $modelEntries = new InvoiceEntriesAmount();

        $model->creator_id = Yii::$app->user->identity->id;
        $model->invoice_number = Invoice::generateInvoiceNumber();
        $model->vat_percentage = Admin::getVatPercentage();
        $model->status = Invoice::STATUS_NOT_PAID;

        $statusDropDown[Invoice::STATUS_NOT_PAID] = Invoice::STATUSES[Invoice::STATUS_NOT_PAID];

        if (  $model->load(Yii::$app->request->post()) && $model->save()  ) {
            $post = Yii::$app->request->post();
            foreach($post['InvoiceEntriesAmount'] as $record) {
                $invoiceEntriesAmount = new InvoiceEntriesAmount();
                $invoiceEntriesAmount->invoice_id = $model->id;
                $invoiceEntriesAmount->amount = $record['amount'];
                $invoiceEntriesAmount->entry_id = $record['entry_id'];
                $invoiceEntriesAmount->save();
            }

            Yii::$app->session->addFlash(
                'success',  Yii::t('auth', 'You have successfully create Invoice № ' . $model->invoice_number)
            );
            $this->redirect('index');

        } else {
            return $this->render('create', [
                'model' => $model,
                'modelEntries' => $modelEntries,
                'appointmentsList' => Appointment::getSelfAppointmentsWithoutInvoiceDropDown(Yii::$app->user->identity),
                'statusesList' => $statusDropDown,
                'createFrom' => false,
                'invoiceEntriesAmount' => [],
                'entryList' => InvoiceEntryDropDown::getAllEntriesListDropDown()
            ]);
        }
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->sub_total_amount_disabled = $model->sub_total_amount;
        $statusDropDown[Invoice::STATUS_NOT_PAID] = Invoice::STATUSES[Invoice::STATUS_NOT_PAID];
        $appointmentsList = Appointment::getSelfAppointmentsWithoutInvoiceDropDown(Yii::$app->user->identity);
        $appointmentsList[$model->appointment_id] = $model->appointment_id;

        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {

            foreach($model->invoiceEntriesAmount as $record) {
                $record->delete();
            }

            $post = Yii::$app->request->post();
            foreach($post['InvoiceEntriesAmount'] as $record) {
                $invoiceEntriesAmount = new InvoiceEntriesAmount();
                $invoiceEntriesAmount->invoice_id = $model->id;
                $invoiceEntriesAmount->amount = $record['amount'];
                $invoiceEntriesAmount->entry_id = $record['entry_id'];
                $invoiceEntriesAmount->save();
            }

            Yii::$app->session->addFlash('success', Yii::t('auth', 'Сhanges saved successfully '));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'modelEntries' => new InvoiceEntriesAmount(),
                'invoiceEntriesAmount' => $model->invoiceEntriesAmount,
                'model' => $model,
                'appointmentsList' =>  $appointmentsList,
                'statusesList' => $statusDropDown,
                'createFrom' => false,
                'entryList' => InvoiceEntryDropDown::getAllEntriesListDropDown()
            ]);
        }
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateInvoice($id)
    {
        $model = new Invoice();
        $modelEntries = new InvoiceEntriesAmount();
        $model->appointment_id = $id;
        $model->creator_id = Yii::$app->user->identity->id;
        $model->invoice_number = Invoice::generateInvoiceNumber();
        $model->vat_percentage = Admin::getVatPercentage();
        $model->status = Invoice::STATUS_NOT_PAID;

        $statusDropDown[Invoice::STATUS_NOT_PAID] = Invoice::STATUSES[Invoice::STATUS_NOT_PAID];
        $appointmentsListDropDown[$id] = $id;

        if (  $model->load(Yii::$app->request->post()) && $model->save()  ) {
            $post = Yii::$app->request->post();
            foreach($post['InvoiceEntriesAmount'] as $record) {
                $invoiceEntriesAmount = new InvoiceEntriesAmount();
                $invoiceEntriesAmount->invoice_id = $model->id;
                $invoiceEntriesAmount->amount = $record['amount'];
                $invoiceEntriesAmount->entry_id = $record['entry_id'];
                $invoiceEntriesAmount->save();
            }

            Yii::$app->session->addFlash(
                'success', Yii::t('auth', 'You have successfully create Invoice № ' .
                $model->invoice_number .', for Appointment № ' .
                $model->appointment->id ));
            $this->redirect('/appointment/index');
        } else {
            return $this->render('create', [
                'modelEntries' => $modelEntries,
                'invoiceEntriesAmount' => [],
                'model' => $model,
                'appointmentsList' => $appointmentsListDropDown,
                'statusesList' => $statusDropDown,
                'createFrom' => true,
                'entryList' => InvoiceEntryDropDown::getAllEntriesListDropDown()
            ]);
        }
    }



    /**
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
        Yii::$app->session->addFlash(
            'warning', Yii::t('auth', 'You have successfully deleted Invoice № ' .   $model->invoice_number ) );
        } else {
            Yii::$app->session->addFlash('error', Yii::t('auth',
                'Something went wrong, Invoice №' . $model->invoice_number . ' not deleted'));
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
