<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "{{%invoice_entries_amount}}".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $entry_id
 * @property integer $amount
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Invoice $invoice
 */
class InvoiceEntriesAmount extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice_entries_amount}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['entry_id','amount'], 'required'],
                [['invoice_id'], 'integer'],
                [['entry_id'], 'string', 'max' => 255],

                [
                    'amount',
                    'compare',
                    'compareValue' => 0,
                    'operator' => '>=',
                    'message' => 'Amount $ cannot be blank'
                ],

                [
                    'amount',
                    'compare',
                    'compareValue' => '0',
                    'operator' => '!==',
                    'message' => 'Amount $ can not be zero'
                ],

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
                'id' => Yii::t('common', 'ID'),
                'invoice_id' => Yii::t('common', 'Invoice ID'),
                'entry_id' => Yii::t('common', 'Entry'),
                'amount' => Yii::t('common', 'Amount $'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
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

    public function getInvoiceEntryDropDown()
    {
        return $this->hasOne(InvoiceEntryDropDown::className(), ['id' => 'invoice_id']);
    }
}
