<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%employees_schedule}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class UserSchedule extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_schedule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['user_id','date','start_time','end_time'], 'required'],
                [['user_id', 'created_at', 'updated_at'], 'integer'],
                [['date'], 'string', 'max' => 255],

                [
                    ['start_time'],
                    'date',
                    'format'=>'php:H:i',
                    'message'=>'invalid time format, please check format like 00:00'
                ],

                [
                    ['end_time'],
                    'date',
                    'format'=>'php:H:i',
                    'message'=>'invalid time format, please check format like 00:00'
                ],

                [
                    ['user_id'],
                    'exist', 'skipOnError' => true,
                    'targetClass' => User::className(),
                    'targetAttribute' => ['user_id' => 'id']
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
                'date' => Yii::t('common', 'Date'),
                'start_time' => Yii::t('common', 'Work From'),
                'end_time' => Yii::t('common', 'Work To'),
                'user_id' => Yii::t('common', 'User'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
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
