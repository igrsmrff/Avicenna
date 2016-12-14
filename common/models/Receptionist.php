<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%receptionists}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $receptionistImageUrl
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Receptionist extends \common\overrides\db\ActiveRecord
{
    public $user_id_validate_flag=true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%receptionists}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['name','phone'],'required'],
                [['name', 'address', 'phone','receptionistImageUrl'], 'string', 'max' => 255],
                [['updated_at', 'created_at'], 'integer'],

                [
                    ['phone'],
                    'match',
                    'pattern' => '/^[0-9]{1}[0-9]{8}[0-9]{1}$/i',
                    'message' => 'Please, enter number like this format XXXXXXXXXX'
                ],

                [
                    ['user_id'],'required',
                    'when' => function ($model) {
                        return $model->user_id_validate_flag;
                    }
                ],

                [
                    ['user_id', ], 'exist',
                    'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['user_id' => 'id'],
                ],

                [
                    'user_id', 'compare',
                    'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose a user'
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
                'name' => Yii::t('common', 'Name'),
                'address' => Yii::t('common', 'Address'),
                'phone' => Yii::t('common', 'Phone'),
                'receptionistImageUrl' => Yii::t('common', 'Receptionist Image Url'),
                'user_id' =>  Yii::t('common', 'Account ID'),
                'created_at' => Yii::t('common', 'Registered'),
                'updated_at' => Yii::t('common', 'Last modified'),
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
