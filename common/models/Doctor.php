<?php

namespace common\models;

use Yii;
use common\models\Department;
use common\models\User;

/**
 * This is the model class for table "{{%doctors}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $profile
 * @property string $doctorImageUrl
 * @property integer $department_id
 * @property integer $user_id
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Appointment[] $appointments
 * @property DoctorPatientPivot[] $doctorPatients
 * @property Department $department
 * @property Prescription[] $prescriptions
 * @property Report[] $reports
 */
class Doctor extends \common\overrides\db\ActiveRecord
{
    public $user_id_validate_flag=true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%doctors}}';
    }

    /**
     * @inheritdoc
     */
    public static function getDoctorsListDropDown()
    {
        $doctorDropDown = [];
        $data = self::find()->asArray()->all();
        if(count($data)) {
            foreach ($data as $key => $value) {
                $doctorDropDown[$value['id']] = $value['name'];
            }
        }
        return $doctorDropDown;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['name','department_id', 'phone'],'required'],
                [['name', 'address', 'phone','doctorImageUrl'], 'string', 'max' => 255],
                [['profile'], 'string'],
                [['id','updated_at', 'created_at'], 'integer'],

                [
                    ['phone'],
                    'match',
                    'pattern' => '/^[0-9]{1}[0-9]{8}[0-9]{1}$/i',
                    'message' => 'Please, enter number like this format XXXXXXXXXX'
                ],

                [
                    ['user_id'],'required', 'when' => function ($model) {
                        return $model->user_id_validate_flag;
                    }
                ],

                [
                    'user_id',
                    'compare',
                    'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose a user'
                ],

                [
                    ['user_id', ], 'exist',
                    'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['user_id' => 'id'],
                ],

                [
                    'department_id',
                    'compare',
                    'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose department'
                ],

                [
                    ['department_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Department::className(),
                    'targetAttribute' => ['department_id' => 'id']
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
                'name' => Yii::t('common', 'Name'),
                'address' => Yii::t('common', 'Address'),
                'phone' => Yii::t('common', 'Phone'),
                'profile' => Yii::t('common', 'Profile'),
                'doctorImageUrl' => Yii::t('common', 'Doctor Image Url'),
                'user_id' =>  Yii::t('common', 'Account ID'),
                'department_id' => Yii::t('common', 'Department'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id' => 'department_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['doctor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReports()
    {
        return $this->hasMany(Report::className(), ['doctor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrescriptions()
    {
        return $this->hasMany(Prescription::className(), ['doctor_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorPatients()
    {
        return $this->hasMany(DoctorPatientPivot::className(), ['doctor_id' => 'id']);
    }

}
