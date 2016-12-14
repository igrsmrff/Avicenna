<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%appointments}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $time
 * @property string $status
 * @property integer $creator_id
 * @property integer $patient_id
 * @property integer $doctor_id
 * @property integer $nurse_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $creator
 * @property Patient $patient
 * @property Doctor $doctor
 * @property Nurse $nurse
 * @property Report $report
 * @property Initialinspection $Initialinspection
 * @property Invoice $invoice
 * @property Sms $sms
 */
class Appointment extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_MISSING = 3;

    const STATUSES_ARRAY = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_INACTIVE => 'inactive',
        self::STATUS_MISSING => 'missing',
    ];

    public static function tableName()
    {
        return '{{%appointments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['date','patient_id','doctor_id','nurse_id','time'], 'required'],

                [['creator_id', 'patient_id', 'created_at', 'updated_at'], 'integer'],

                [['date', 'status'], 'string', 'max' => 255],

                [
                    ['date'],
                    'date', 'format' => 'php:Y-m-d',
                    'message'=>'invalid date format, please check format like 1970-01-01'
                ],

                [
                    ['time'],
                    'date',
                    'format'=>'php:H:i',
                    'message'=>'invalid time format, please check format like 00:00'
                ],

                ['date', 'custom_date_validation' ],
                ['time', 'custom_time_validation' ],

                [
                    ['creator_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['creator_id' => 'id']
                ],

                [
                    ['patient_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => Patient::className(),
                    'targetAttribute' => ['patient_id' => 'id']
                ],

                [
                    ['doctor_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => Doctor::className(),
                    'targetAttribute' => ['doctor_id' => 'id']
                ],

                [
                    ['nurse_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => Nurse::className(),
                    'targetAttribute' => ['nurse_id' => 'id']
                ],

           ]
        );
    }

    public function custom_date_validation($attribute, $params)
    {
        $daySchedule = UserSchedule::find()
            ->where(['user_id'=>$this->doctor->user->id])
            ->andWhere(['date'=> $this->date])
            ->one();

        if( is_null($daySchedule) ) {
            $this->addError($attribute, 'sorry, but ' .
            $this->doctor->name . ' doesn\'t work on this day ');
        }

    }

    public function custom_time_validation($attribute, $params)
    {
        $daySchedule = UserSchedule::find()
            ->where(['user_id'=>$this->doctor->user->id])
            ->andWhere(['date'=> $this->date])
            ->one();

        if ($daySchedule) {

            $validateTime = strtotime($this->time);
            $start_time = strtotime($daySchedule->start_time);
            $end_time = strtotime($daySchedule->end_time);

            if( !is_null($daySchedule) ) {
                if( !($start_time <= $validateTime  && $validateTime < $end_time) ) {
                    $this->addError($attribute, 'sorry, but ' .
                        $this->doctor->name . ' doesn\'t work at this time, his work time is: ' .
                        $daySchedule->start_time . '-' .
                        $daySchedule->end_time);
                }
            }
        }
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
                'date' => Yii::t('common', 'Date'),
                'time' => Yii::t('common', 'Time'),
                'status' => Yii::t('common', 'Status'),
                'patient_id' => Yii::t('common', 'Patient'),
                'doctor_id' => Yii::t('common', 'Doctor'),
                'nurse_id' => Yii::t('common', 'Nurse'),
                'creator_id' => Yii::t('common', 'Created By'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatorEntity()
    {
        $className = ucfirst(User::$roles[$this->creator->role]);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $this->creator_id])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatorTableName()
    {
        $className = User::$roles[$this->getCreator()->one()->role];
        $className = ucfirst($className);
        $className = "common\\models\\" . $className;
        return $className::tableName();
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id' => 'patient_id']);
    }

    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id' => 'doctor_id']);
    }

    public function getNurse()
    {
        return $this->hasOne(Nurse::className(), ['id' => 'nurse_id']);
    }

    public function getSms()
    {
        return $this->hasOne(Sms::className(), ['appointment_id' => 'id']);
    }

    public function getReport()
    {
        return $this->hasOne(Report::className(), ['appointment_id' => 'id']);
    }

    public function getInitialinspection()
    {
        return $this->hasOne(Initialinspection::className(), ['appointment_id' => 'id']);
    }

    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['appointment_id' => 'id']);
    }

    public static function getAppointmentsListDropDown()
    {
        $appointmentDropDown = [];
        $appointments = self::find()->asArray()->all();
        if(count($appointments)) {
            foreach ($appointments as $appointment) {
                $appointmentDropDown[$appointment['id']] = $appointment['id'];
            }
        }
        return $appointmentDropDown;
    }

    public static function getAllAppointmentsWithoutSmsOnCurrentDate($date)
    {
        $query=new Query();
        $query
            ->select([Appointment::tableName() . '.*'])
            ->from ([Appointment::tableName()])
            ->leftJoin([Sms::tableName(). " ON " .
                Appointment::tableName() . ".id =" .
                Sms::tableName() . ".appointment_id" ])
            ->where(
                [
                    Sms::tableName() . ".appointment_id" => NULL,
                    Appointment::tableName() . ".date"  => $date
                ]
            );
        return   $query->all();
    }

    public static function getAllAppointmentsWithoutInitialInspectionDropDown()
    {
        $query=new Query();
        $appointmentsListDropDown = [];
        $query
            ->select([Appointment::tableName() . '.*'])
            ->from ([Appointment::tableName()])
            ->leftJoin([Initialinspection::tableName(). " ON " .
                Appointment::tableName() . ".id =" .
                Initialinspection::tableName() . ".appointment_id" ])
            ->where([Initialinspection::tableName(). ".appointment_id" => NULL]);

        $data = $query->all();

        if(count($data)) {
            foreach ($data as $key => $value) {
                $appointmentsListDropDown[$value['id']] = $value['id'];
            }
        }
        return $appointmentsListDropDown;
    }
    public static function getSelfAppointmentsWithoutInitialInspectionDropDown($user)
    {
        if ($user->role == User::ROLE_DOCTOR || $user->role == User::ROLE_NURSE) {

            $query=new Query();
            $appointmentsListDropDown = [];

            $query
                ->select([Appointment::tableName() . '.*'])
                ->from ([Appointment::tableName()])
                ->leftJoin([Initialinspection::tableName(). " ON " .
                    Appointment::tableName() . ".id =" .
                    Initialinspection::tableName() . ".appointment_id" ])
                ->where(
                    [
                        Initialinspection::tableName(). ".appointment_id" => NULL,
                        Appointment::tableName(). "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ] );

            $data = $query->all();

            if(count($data)) {
                foreach ($data as $key => $value) {
                    $appointmentsListDropDown[$value['id']] = $value['id'];
                }
            }
            return $appointmentsListDropDown;

        } else {
            return self::getAllAppointmentsWithoutInitialInspectionDropDown();
        }
    }

    public static function getAllAppointmentsWithoutSmsDropDown()
    {
        $query=new Query();
        $appointmentsListDropDown = [];
        $query
            ->select([Appointment::tableName() . '.*'])
            ->from ([Appointment::tableName()])
            ->leftJoin([Sms::tableName(). " ON " .
                Appointment::tableName() . ".id =" .
                Sms::tableName() . ".appointment_id" ])
            ->where([Sms::tableName() . ".appointment_id" => NULL]);

        $appointments = $query->all();

        if(count($appointments)) {
            foreach ($appointments as $appointment) {
                $appointmentsListDropDown[$appointment['id']] = $appointment['id'];
            }
        }
        return $appointmentsListDropDown;
    }
    public static function getSelfAppointmentsWithoutSmsDropDown($user)
    {
        if ( $user->role == User::ROLE_DOCTOR ) {

            $query=new Query();
            $appointmentsListDropDown = [];

            $query
                ->select([Appointment::tableName() . '.*'])
                ->from ([Appointment::tableName()])
                ->leftJoin([Sms::tableName(). " ON " .
                    Appointment::tableName() . ".id =" .
                    Sms::tableName() . ".appointment_id" ])
                ->where(
                    [
                        Sms::tableName(). ".appointment_id" => NULL,
                        Appointment::tableName(). "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ] );

            $reports = $query->all();

            if(count($reports)) {
                foreach ($reports as $report) {
                    $appointmentsListDropDown[$report['id']] = $report['id'];
                }
            }
            return $appointmentsListDropDown;

        } else {
            return self::getAllAppointmentsWithoutReportDropDown();
        }
    }

    public static function getAllAppointmentsWithoutReportDropDown()
    {
        $query=new Query();
        $appointmentsListDropDown = [];
        $query
            ->select([Appointment::tableName() . '.*'])
            ->from ([Appointment::tableName()])
            ->leftJoin([Report::tableName(). " ON " .
                Appointment::tableName() . ".id =" .
                Report::tableName() . ".appointment_id" ])
            ->where([Report::tableName(). ".appointment_id" => NULL]);

        $appointments = $query->all();

        if(count($appointments)) {
            foreach ($appointments as $appointment) {
                $appointmentsListDropDown[$appointment['id']] = $appointment['id'];
            }
        }
        return $appointmentsListDropDown;
    }
    public static function getSelfAppointmentsWithoutReportDropDown($user)
    {
        if ( $user->role == User::ROLE_DOCTOR ) {

            $query=new Query();
            $appointmentsListDropDown = [];

            $query
                ->select([Appointment::tableName() . '.*'])
                ->from ([Appointment::tableName()])
                ->leftJoin([Report::tableName(). " ON " .
                    Appointment::tableName() . ".id =" .
                    Report::tableName() . ".appointment_id" ])
                ->where(
                    [
                        Report::tableName(). ".appointment_id" => NULL,
                        Appointment::tableName(). "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ] );

            $reports = $query->all();

            if(count($reports)) {
                foreach ($reports as $report) {
                    $appointmentsListDropDown[$report['id']] = $report['id'];
                }
            }
            return $appointmentsListDropDown;

        } else {
            return self::getAllAppointmentsWithoutReportDropDown();
        }
    }

    public static function getAllAppointmentsWithoutInvoiceDropDown()
    {
        $query=new Query();
        $appointmentsListDropDown = [];
        $query
            ->select([Appointment::tableName() . '.*'])
            ->from ([Appointment::tableName()])
            ->leftJoin([Invoice::tableName(). " ON " .
                Appointment::tableName() . ".id =" .
                Invoice::tableName() . ".appointment_id" ])
            ->where([Invoice::tableName(). ".appointment_id" => NULL]);

        $appointments = $query->all();

        if(count($appointments)) {
            foreach ($appointments as $appointment) {
                $appointmentsListDropDown[$appointment['id']] = $appointment['id'];
            }
        }
        return $appointmentsListDropDown;
    }
    public static function getSelfAppointmentsWithoutInvoiceDropDown($user)
    {
        if ($user->role == User::ROLE_DOCTOR) {
            $query=new Query();
            $appointmentsListDropDown = [];
            $query
                ->select([Appointment::tableName() . '.*'])
                ->from ([Appointment::tableName()])
                ->leftJoin([Invoice::tableName(). " ON " .
                    Appointment::tableName() . ".id =" .
                    Invoice::tableName() . ".appointment_id" ])
                ->where(
                    [
                        Invoice::tableName(). ".appointment_id" => NULL,
                        Appointment::tableName(). "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ]
                );

            $appointments = $query->all();

            if(count($appointments)) {
                foreach ($appointments as $appointment) {
                    $appointmentsListDropDown[$appointment['id']] = $appointment['id'];
                }
            }
            return $appointmentsListDropDown;
        } else {
            return self::getAllAppointmentsWithoutInvoiceDropDown();
        }
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
