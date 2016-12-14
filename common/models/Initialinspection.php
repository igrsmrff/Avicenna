<?php

namespace common\models;

use Yii;
use common\models\User;
use common\models\Patient;

/**
 * This is the model class for table "{{%initial_inspections}}".
 *
 * @property integer $id
 * @property string $weight
 * @property string $height
 * @property string $blood_pressure
 * @property string $temperature
 * @property integer $appointment_id
 * @property integer $creator_id
 * @property string $notes
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Appointment $appointment
 */
class Initialinspection extends \common\overrides\db\ActiveRecord
{
    public $patient_id_validate_flag = true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%initial_inspections}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['notes'], 'string'],
                [['appointment_id', 'creator_id', 'created_at', 'updated_at'], 'integer'],
                [['weight', 'height', 'blood_pressure', 'temperature'], 'string', 'max' => 255],

                [
                    ['creator_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['creator_id' => 'id']
                ],

                [
                    ['appointment_id'],
                    'exist',
                    'skipOnError' => true,
                    'targetClass' => Appointment::className(),
                    'targetAttribute' => ['appointment_id' => 'id'], 'when' => function ($model) {
                    return $model->patient_id_validate_flag;}
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
            [
                'id' => Yii::t('common', 'ID'),
                'weight' => Yii::t('common', 'Weight'),
                'height' => Yii::t('common', 'Height'),
                'blood_pressure' => Yii::t('common', 'Blood Pressure'),
                'temperature' => Yii::t('common', 'Temperature'),
                'appointment_id' => Yii::t('common', 'Appointment ID'),
                'notes' => Yii::t('common', 'Notes'),
                'creator_id' => Yii::t('common', 'Created By'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    public function getCreatorEntity()
    {
        $className = ucfirst(User::$roles[$this->creator->role]);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $this->creator_id])->one();
    }


    public function getAppointment()
    {
        return $this->hasOne(Appointment::className(), ['id' => 'appointment_id']);
    }

    public function getPatient()
    {
        return $this
            ->hasOne(Appointment::className(), ['id' => 'appointment_id'])
            ->one()
            ->getPatient();
    }

    public function getDoctor()
    {
        return $this
            ->hasOne(Appointment::className(), ['id' => 'appointment_id'])
            ->one()
            ->getDoctor();
    }

    public function getNurse()
    {
        return $this
            ->hasOne(Appointment::className(), ['id' => 'appointment_id'])
            ->one()
            ->getNurse();
    }

    public static function abilityCreate($role)
    {
        switch ($role) {
            case User::ROLE_ADMIN:
                return true;
                break;
            case User::ROLE_DOCTOR:
                return false;
                break;
            case User::ROLE_NURSE:
                return true;
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
            default:
                return false;
        }
    }
}
