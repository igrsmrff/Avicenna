<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%payments}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $payment_method
 * @property integer $invoice_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Invoice $invoice
 */
class Payment extends \common\overrides\db\ActiveRecord
{
    const PAYMENT_METHOD_CASH = 1;
    const PAYMENT_METHOD_BANK = 2;
    const PAYMENT_METHOD_TRANSFER = 3;

    const PAYMENT_METHODS_ARRAY = [
        self::PAYMENT_METHOD_CASH => 'cash',
        self::PAYMENT_METHOD_BANK => 'bank',
        self::PAYMENT_METHOD_TRANSFER => 'transfer'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['payment_method'], 'required'],
                [['invoice_id', 'created_at', 'updated_at'], 'integer'],
                [
                    ['invoice_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Invoice::className(),
                    'targetAttribute' => ['invoice_id' => 'id']
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
                'title' => Yii::t('frontend', 'Title'),
                'description' => Yii::t('frontend', 'Description'),
                'payment_method' => Yii::t('frontend', 'Payment Method'),
                'invoice_id' => Yii::t('frontend', 'Invoice'),
                'created_at' => Yii::t('frontend', 'Created At'),
                'updated_at' => Yii::t('frontend', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    public static function abilityCreate($role) {
        switch ($role) {
            case User::ROLE_ADMIN:
                return true;
                break;
            case User::ROLE_DOCTOR:
                return false;
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
