<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "{{%doctor_patient}}".
 *
 * @property integer $id
 * @property integer $doctor_id
 * @property integer $patient_id
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Patient $patient
 * @property Doctor $doctor
 */
class DoctorPatientPivot extends \common\overrides\db\ActiveRecord
{
    public $patientEntity;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%doctor_patient}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['doctor_id', 'patient_id'], 'integer'],
                [['updated_at', 'created_at'], 'safe'],
                [
                    ['patient_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Patient::className(),
                    'targetAttribute' => ['patient_id' => 'id']
                ],
                [
                    ['doctor_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Doctor::className(),
                    'targetAttribute' => ['doctor_id' => 'id']
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
                'doctor_id' => Yii::t('common', 'Doctor ID'),
                'patient_id' => Yii::t('common', 'Patient ID'),
                'updated_at' => Yii::t('common', 'Last modified'),
                'created_at' => Yii::t('common', 'Registered'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'doctor_id']);
    }


    public static function createNewRecordIfNotExist( $doctor_id, $patient_id){

        $record = DoctorPatientPivot::find()
            ->where(['patient_id'=>$patient_id,'doctor_id'=>$doctor_id ])
            ->one();

        if(is_null($record)){
            $record = new self();
            $record->patient_id = $patient_id;
            $record->doctor_id = $doctor_id;
            $record->save();
        }
        return $record;
    }
}
