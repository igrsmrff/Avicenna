<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%admins}}".
 *
 * @property integer $id
 * @property string $system_name
 * @property string $system_title
 * @property string $name
 * @property string $address
 * @property string $paypal_email
 * @property string $currency
 * @property string $phone
 * @property string $phone_country_code
 * @property string $integer
 * @property string $system_email
 * @property string $adminImageUrl
 * @property integer $user_id
 * @property string $language
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Admin extends \common\overrides\db\ActiveRecord
{
    const ADMIN_ID = 1;
    public $user_id_validate_flag=true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admins}}';
    }

    public static function getVatPercentage()
    {
        return Admin::findOne(1)->vat_percentage;
    }

    public static function getPhoneCountryCode()
    {
        return Admin::findOne(1)->phone_country_code;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [
                    [
                        'system_name',
                        'system_title',
                        'paypal_email',
                        'system_email',
                        'vat_percentage',
                        'phone_country_code',
                        'phone'
                    ],

                    'required'
                ],

                [['paypal_email', 'system_email'], 'email'],

                [['created_at', 'updated_at', 'vat_percentage'], 'integer'],

                [
                    ['vat_percentage'],
                    'match', 'pattern' => '/^[1-9]{1}[0-9]{0,1}$/i',
                    'message' => 'you can enter only numbers'
                ],

                [
                    ['phone_country_code'],
                    'match', 'pattern' => '/^[+]{1}[0-9]{2}$/i',
                    'message' => 'you can enter counry code like +XX'
                ],


                [
                    [
                        'system_name',
                        'system_title',
                        'name', 'address',
                        'currency', 'phone',
                        'adminImageUrl',
                        'language' ,
                        'vat_percentage'
                    ],

                    'string', 'max' => 255],

                [
                    ['user_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['user_id' => 'id'],
                    'when' => function ($model) {
                        return $model->user_id_validate_flag;
                    }
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
                'system_name' => Yii::t('common', 'System Name'),
                'system_title' => Yii::t('common', 'System Title'),
                'name' => Yii::t('common', 'Name'),
                'address' => Yii::t('common', 'Address'),
                'paypal_email' => Yii::t('common', 'Paypal Email'),
                'vat_percentage' => Yii::t('common', 'Vat Percentage'),
                'currency' => Yii::t('common', 'Currency'),
                'phone' => Yii::t('common', 'System Phone'),
                'phone_сountry_code' => Yii::t('common', 'Phone Country Code'),
                'system_email' => Yii::t('common', 'System Email'),
                'adminImageUrl' => Yii::t('common', 'Admin Image Url'),
                'user_id' => Yii::t('common', 'User ID'),
                'language' => Yii::t('common', 'Language'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
