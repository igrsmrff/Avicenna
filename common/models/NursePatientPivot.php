<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "{{%nurse_patient}}".
 *
 * @property integer $id
 * @property integer $nurse_id
 * @property integer $patient_id
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Patient $patient
 * @property Nurse $nurse
 */
class NursePatientPivot extends \common\overrides\db\ActiveRecord
{
    public $patientEntity;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nurse_patient}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['nurse_id', 'patient_id'], 'integer'],
                [['updated_at', 'created_at'], 'safe'],
                [
                    ['patient_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Patient::className(),
                    'targetAttribute' => ['patient_id' => 'id']
                ],
                [
                    ['nurse_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Nurse::className(),
                    'targetAttribute' => ['nurse_id' => 'id']
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
                'nurse_id' => Yii::t('common', 'Nurse ID'),
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
    public function getNurse()
    {
        return $this->hasOne(Nurse::className(), ['id' => 'nurse_id']);
    }


    public static function createNewRecordIfNotExist( $nurse_id, $patient_id){

        $record = NursePatientPivot::find()
            ->where(['patient_id'=>$patient_id, 'nurse_id'=>$nurse_id])
            ->one();

        if(is_null($record)){
            $record = new self();
            $record->patient_id = $patient_id;
            $record->nurse_id = $nurse_id;
            $record->save();
        }
        return $record;
    }
}
