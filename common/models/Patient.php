<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%patients}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $birth_date
 * @property string $insurance_expiration_date
 * @property integer $insurance_company_id
 * @property string $insurance_number
 * @property integer $sex
 * @property integer $marital_status
 * @property string $patientImageUrl
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Appointment[] $appointments
 * @property BedAllotment[] $bedsAllotments
 * @property DoctorPatientPivot[] $doctorPatients
 * @property InsuranceCompany $insuranceCompany
 */
class Patient extends \common\overrides\db\ActiveRecord
{

    const MALE = 1;
    const FEMALE = 2;
    const SEX_ARRAY = [
        self::MALE => 'male',
        self::FEMALE => 'female'
    ];

    const  MARITAL_STATUS_NOT_MARRIED = 1;
    const  MARITAL_STATUS_MARRIED = 2;
    const MARITAL_STATUS_ARRAY = [
        self::MARITAL_STATUS_NOT_MARRIED =>'married',
        self::MARITAL_STATUS_MARRIED => 'not married'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%patients}}';
    }

    public static function getPatientsToForm($q = null)
    {
        return self::find()->select('id, name')
            ->from(User::tableName())
            ->where(['like', 'name', $q])
            ->limit(20)
            ->active()
            ->asArray()
            ->all();
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
                        'name',
                        'phone',
                        'sex',
                        'insurance_company_id',
                        'insurance_number',
                        'insurance_expiration_date'
                    ],
                    'required'
                ],


                [
                    'sex',
                    'compare', 'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose sex'
                ],

                [
                    'marital_status',
                    'compare', 'compareValue' => 1,
                    'operator' => '>=',
                    'message' => 'Please choose sex'
                ],

                [
                    ['phone'],
                    'match',
                    'pattern' => '/^[0-9]{1}[0-9]{8}[0-9]{1}$/i',
                    'message' => 'Please, enter number like this format XXXXXXXXXX'
                ],

                [
                    ['insurance_number'],
                    'match',
                    'pattern' => '/^[0-9]{1}[0-9]*[0-9]{0}$/i',
                    'message' => 'you can enter only numbers, in this field'
                ],

                [
                    ['insurance_company_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => InsuranceCompany::className(),
                    'targetAttribute' => ['insurance_company_id' => 'id']
                ],

                [
                    ['birth_date'],
                    'date', 'format' => 'php:Y-m-d',
                    'message'=>'invalid date format, please check format like 1970-01-01, '
                ],

                [
                    ['insurance_expiration_date'],
                    'date', 'format' => 'php:Y-m-d',
                    'message'=>'invalid date format, please check format like 1970-01-01, '
                ],

                [['insurance_company_id', 'sex', 'marital_status', 'created_at', 'updated_at'], 'integer'],
                [['name', 'address', 'phone', 'birth_date', 'insurance_expiration_date', 'insurance_number', 'patientImageUrl'], 'string', 'max' => 255],

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
                'birth_date' => Yii::t('common', 'Birth Date'),
                'insurance_expiration_date' => Yii::t('common', 'Insurance Expiration Date'),
                'insurance_company_id' => Yii::t('common', 'Insurance Company'),
                'insurance_number' => Yii::t('common', 'Insurance Number'),
                'sex' => Yii::t('common', 'Sex'),
                'marital_status' => Yii::t('common', 'Marital Status'),
                'patientImageUrl' => Yii::t('common', 'Patient Image Url'),
                'created_at' => Yii::t('common', 'Registered'),
                'updated_at' => Yii::t('common', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['patient_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorPatients()
    {
        return $this->hasMany(DoctorPatientPivot::className(), ['patient_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceCompany()
    {
        return $this->hasOne(InsuranceCompany::className(), ['id' => 'insurance_company_id']);
    }


    public static function getPatientsListDropDown()
    {
        $patientDropDown = [];
        $data = self::find()->asArray()->all();
        if(count($data)) {
            foreach ($data as $key => $value) {
                $patientDropDown[$value['id']] = $value['name'] . ' phone: ' .$value['phone'];
            }
        }
        return $patientDropDown;
    }

    public static function getAllPatientsWithoutAppointmentDropDown()
    {
        $query=new Query();
        $patientsListDropDown = [];
        $query
            ->select([Patient::tableName() . '.*'])
            ->from ([Patient::tableName()])
            ->leftJoin([Appointment::tableName(). " ON " .
                Patient::tableName() . ".id =" .
                Appointment::tableName() . ".patient_id" ])
            ->where([Appointment::tableName(). ".patient_id" => NULL]);

        $patients = $query->all();

        if(count($patients)) {
            foreach ($patients as $patient) {
                $patientsListDropDown[$patient['id']] = $patient['name'];
            }
        }
        return $patientsListDropDown;
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
