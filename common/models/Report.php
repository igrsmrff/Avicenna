<?php

namespace common\models;

use Yii;
use yii\db\Query;
/**
 * This is the model class for table "{{%reports}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $creator_id
 * @property integer $appointment_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Prescription $prescription
 * @property Patient $patient
 * @property Doctor $doctor
 */
class Report extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reports}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['title'], 'required'],
                [['description'], 'string'],
                [['creator_id', 'appointment_id', 'created_at', 'updated_at'], 'integer'],
                [['title'], 'string', 'max' => 255],

                [
                    ['appointment_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Appointment::className(),
                    'targetAttribute' => ['appointment_id' => 'id']
                ],

                [
                    ['creator_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['creator_id' => 'id']
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
                'id' => Yii::t('frontend', 'ID'),
                'title' => Yii::t('frontend', 'Title'),
                'description' => Yii::t('frontend', 'Description'),
                'appointment_id' => Yii::t('frontend', 'Appointment ID'),
                'creator_id' => Yii::t('frontend', 'Created By'),
                'created_at' => Yii::t('frontend', 'Created At'),
                'updated_at' => Yii::t('frontend', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrescription()
    {
        return $this->hasOne(Prescription::className(), ['report_id' => 'id']);
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

    public function getCreatorEntity()
    {
        $className = ucfirst(User::$roles[$this->creator->role]);
        $className = "common\\models\\" . $className;
        return $className::find()->where(['user_id' => $this->creator_id])->one();
    }

    public static function getAllReportsWithoutPrescriptionDropDown()
    {
        $reportsListDropDown = [];
        $query=new Query();
        $query
            ->select([Report::tableName() . '.*'])
            ->from ([Report::tableName()])
            ->leftJoin([Prescription::tableName(). " ON " .
                Report::tableName() . ".id =" .
                Prescription::tableName() . ".report_id" ])
            ->where([Prescription::tableName(). ".report_id" => NULL] );

        $data = $query->all();

        if(count($data)) {
            foreach ($data as $key => $value) {
                $reportsListDropDown[$value['id']] = $value['title'];
            }
        }
        return $reportsListDropDown;
    }

    public static function getSelfReportsWithoutPrescriptionDropDown($user)
    {
        if ( $user->role == User::ROLE_DOCTOR ) {
            $reportsListDropDown = [];
            $query = new Query();
            $query
                ->select([Report::tableName() . '.*'])
                ->from([Report::tableName()])
                ->leftJoin([Prescription::tableName() . " ON " .
                    Report::tableName() . ".id =" .
                    Prescription::tableName() . ".report_id"])
                ->leftJoin([Appointment::tableName() . " ON " .
                    Report::tableName() . ".appointment_id =" .
                    Appointment::tableName() . ".id"])
                ->where(
                    [
                        Prescription::tableName() . ".report_id" => NULL,
                        Appointment::tableName() . "." . User::$roles[$user->role] . "_id" => $user->entity->id
                    ]);

            $data = $query->all();

            if (count($data)) {
                foreach ($data as $key => $value) {
                    $reportsListDropDown[$value['id']] = $value['title'];
                }
            }
            return $reportsListDropDown;
        } else {
            return self::getAllReportsWithoutPrescriptionDropDown();
        }
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
