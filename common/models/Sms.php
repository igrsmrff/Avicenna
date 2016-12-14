<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sms}}".
 *
 * @property integer $id
 * @property string $text_message
 * @property string $message_identifier
 * @property integer $status
 * @property integer $appointment_id
 * @property integer $creator_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Appointment $appointment
 */
class Sms extends \common\overrides\db\ActiveRecord
{
    const STATUS_NOT_SENT= 1;
    const STATUS_SENT = 2;

    const STATUSES_ARRAY = [
        self::STATUS_NOT_SENT => 'not sent',
        self::STATUS_SENT => 'sent',

    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['appointment_id', 'message_identifier', 'text_message'], 'required'],

                [['message_identifier', 'text_message'], 'string'],
                [['status', 'creator_id', 'appointment_id', 'created_at', 'updated_at'], 'integer'],
                [
                    ['appointment_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => Appointment::className(),
                    'targetAttribute' => ['appointment_id' => 'id']
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
                'text_message' => Yii::t('common', 'Text Message'),
                'message_identifier' => Yii::t('common', 'Twilio Message Identifier'),
                'status' => Yii::t('common', 'Status'),
                'appointment_id' => Yii::t('common', 'Appointment ID'),
                'creator_at' => Yii::t('common', 'Created By'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
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

    public static function abilityCreate($role)
    {
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
                return true;
                break;
            case User::ROLE_LABORATORIST:
                return false;
                break;
            default:
                return false;
        }
    }
}
