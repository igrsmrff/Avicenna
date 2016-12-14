<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%invoices}}".
 *
 * @property integer $id
 * @property string $invoice_number
 * @property string $due_date
 * @property string $status
 * @property string $vat_percentage
 * @property string $discount_amount
 * @property string $discount_amount_percent
 * @property string $total_invoice_amount
 * @property string $sub_total_amount
 * @property integer $appointment_id
 * @property integer $creator_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $creator
 * @property Appointment $appointment
 * @property Payment $payment
 */
class Invoice extends \common\overrides\db\ActiveRecord
{
    public $entry;
    public $amount;
    public $sub_total_amount_disabled;

    const  STATUS_NOT_PAID = 1;
    const  STATUS_PAID = 2;
    const STATUSES = [
        self::STATUS_NOT_PAID => 'Not paid',
        self::STATUS_PAID => 'Paid'
    ];

    public static function setStatus($id, $status)
    {
        $invoice = self::findOne($id);
        if ($invoice) {
            $invoice->status = $status;
            if ($invoice->save() ) {return true;}
            else {return false;}
        } else {return false;}
    }

    public static function generateInvoiceNumber ()
    {
        $date = date('Y-m-d H:i:s',  time() );
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);
        $hour = substr($date, 11, 2);
        $minutes = substr($date, 14, 2);
        $seconds = substr($date, 17, 2);
        return $year . $month . $day . $hour . $minutes . $seconds;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['invoice_number', 'due_date', 'total_invoice_amount'], 'required'],

                [
                    ['due_date'],
                    'date',
                    'format' => 'php:Y-m-d',
                    'message'=>'invalid date format, please check format like 1970-01-01, '
                ],

                [
                    'status',
                    'compare',
                    'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose status'
                ],


                [['sub_total_amount'], 'safe'],

                [[
                    'id',
                    'status',
                    'appointment_id',
                    'creator_id',
                    'created_at',
                    'updated_at'
                ], 'integer'],

                [[
                    'invoice_number',
                    'due_date',
                    'vat_percentage',
                    'discount_amount',
                    'total_invoice_amount',
                    'discount_amount',
                    'discount_amount_percent'
                ], 'string', 'max' => 255],


                [
                    ['invoice_number'],
                    'match', 'pattern' => '/^[0-9]{1}[0-9]*[0-9]{0}$/i',
                    'message' => 'you can enter only numbers'
                ],

                [
                    ['creator_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['creator_id' => 'id']
                ],

                [
                    ['appointment_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => Appointment::className(),
                    'targetAttribute' => ['appointment_id' => 'id'],
                    'message' => 'Please choose appointment'
                ],
           ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::rules(),
            [
                'id' => Yii::t('frontend', 'ID'),
                'invoice_number' => Yii::t('frontend', 'Invoice Number'),
                'due_date' => Yii::t('frontend', 'Due Date'),
                'status' => Yii::t('frontend', 'Status'),
                'vat_percentage' => Yii::t('frontend', 'Vat Percentage %'),
                'discount_amount' => Yii::t('frontend', 'Discount $'),
                'discount_amount_percent' => Yii::t('frontend', 'Discount %'),
                'sub_total_amount' => Yii::t('frontend', 'Sub Total Amount $'),
                'total_invoice_amount' => Yii::t('frontend', 'Total Invoice Amount $'),
                'appointment_id' => Yii::t('frontend', 'Appointment ID'),
                'creator_id' => Yii::t('frontend', 'Created By'),
                'created_at' => Yii::t('frontend', 'Created At'),
                'updated_at' => Yii::t('frontend', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppointment()
    {
        return $this->hasOne(Appointment::className(), ['id' => 'appointment_id']);
    }

    public function getInvoiceEntriesAmount()
    {
        return $this->hasMany(InvoiceEntriesAmount::className(), ['invoice_id' => 'id']);
    }


    public function getInvoiceEntriesAmountArray()
    {
        $entriesForWidget =[];
        $entries = $this->invoiceEntriesAmount;

        foreach ($entries as $entry) {

            $entriesForWidget[] =  [
                'attribute' => 'Entry: ' . $entry['entry'],
                'format' => 'raw',
                'value' => $entry['amount']
            ];
        }

        return $entriesForWidget;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['invoice_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatorEntity()
    {
        $className = ucfirst(User::$roles[$this->creator->role]);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $this->creator_id])->one();
    }

    public static function getAllInvoicesWithoutPaymentDropDown()
    {
        $invoicesListDropDown = [];
        $invoices = Invoice::find()->where(['status' => self::STATUS_NOT_PAID])->asArray()->all();
        if($invoices) {
            foreach ($invoices as $invoice) {
                $invoicesListDropDown[$invoice['id']] = 'Invoice № ' .
                    $invoice['invoice_number'] . ',  Total Invoice Amount: ' .
                    $invoice['total_invoice_amount'] . ' $';
            }
        }
        return $invoicesListDropDown;
    }

    public static function getSelfInvoicesWithoutPaymentDropDown($user)
    {
        if ($user->role == User::ROLE_DOCTOR) {

            $query=new Query();
            $invoicesListDropDown = [];

            $query
                ->select([Invoice::tableName() . '.*'])
                ->from ([Invoice::tableName()])
                ->leftJoin([Payment::tableName(). " ON " .
                    Invoice::tableName() . ".id =" .
                    Payment::tableName() . ".invoice_id" ])
                ->leftJoin([Appointment::tableName() . " ON " .
                    Invoice::tableName() . ".appointment_id =" .
                    Appointment::tableName() . ".id"])
                ->where(
                    [
                        Payment::tableName(). ".invoice_id" => NULL,
                        Appointment::tableName() . "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ] );

            $invoices = $query->all();

            if(count($invoices)) {
                foreach ($invoices as $invoice) {
                    $invoicesListDropDown[$invoice['id']] = 'Invoice № ' .
                        $invoice['invoice_number'] . ',  Total Invoice Amount: ' .
                        $invoice['total_invoice_amount'] . ' $';
                }
            }
            return $invoicesListDropDown;

        } else {
            return self::getAllInvoicesWithoutPaymentDropDown();
        }
    }

    public static function abilityCreate($role) {
        switch ($role) {
            case User::ROLE_ADMIN:
                return true;
                break;
            case User::ROLE_DOCTOR:
                return true;
                break;
            case User::ROLE_NURSE:
                return false;
                break;
            case User::ROLE_PATIENT:
                return false;
                break;
            case User::ROLE_PHARMACIST:
                return false;
                break;
            case User::ROLE_RECEPTIONIST:
                return false;
                break;
            case User::ROLE_LABORATORIST:
                return false;
                break;
            case User::ROLE_ACCOUNTANT:
                return true;
                break;
            default:
                return false;
        }
    }
}
