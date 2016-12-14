<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%nurses}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $nurseImageUrl
 * @property integer $department_id
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property InitialInspection[] $initialInspections
 * @property User $user
 * @property Department $department
 */
class Nurse extends \common\overrides\db\ActiveRecord
{
    public $user_id_validate_flag=true;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%nurses}}';
    }

    /**
     * @inheritdoc
     */
    public static function getNursesListDropDown()
    {
        $nursesDropDown = [];
        $data = self::find()->asArray()->all();
        if(count($data)) {
            foreach ($data as $key => $value) {
                $nursesDropDown[$value['id']] = $value['name'];
            }
        }
        return $nursesDropDown;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['name', 'phone', 'department_id',],'required'],
                [['name', 'address', 'phone', 'nurseImageUrl'], 'string', 'max' => 255],

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
                    ['user_id',], 'exist',
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
            parent::rules(),
            [
                'id' => Yii::t('common', 'ID'),
                'name' => Yii::t('common', 'Name'),
                'address' => Yii::t('common', 'Address'),
                'phone' => Yii::t('common', 'Phone'),
                'nurseImageUrl' => Yii::t('common', 'Nurse Image Url'),
                'department_id' => Yii::t('common', 'Department'),
                'user_id' => Yii::t('common', 'Account ID'),
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
        return $this->hasMany(Appointment::className(), ['nurse_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInitialInspections()
    {
        return $this->hasMany(Initialinspection::className(), ['nurse_id' => 'id']);
    }

}
