<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%prescriptions}}".
 *
 * @property integer $id
 * @property string $medication
 * @property string $note
 * @property integer $creator_id
 * @property integer $report_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Report $report
 * @property User $creator
 */
class Prescription extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prescriptions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['medication', 'note'], 'string'],
                [['creator_id', 'report_id', 'created_at', 'updated_at'], 'integer'],

                [
                    ['report_id'], 'exist',
                    'skipOnError' => true,
                    'targetClass' => Report::className(),
                    'targetAttribute' => ['report_id' => 'id']
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
            parent::rules(),
            [
                'id' => Yii::t('frontend', 'ID'),
                'medication' => Yii::t('frontend', 'Medication'),
                'note' => Yii::t('frontend', 'Note'),
                'report_id' => Yii::t('frontend', 'Report'),
                'creator_id' => Yii::t('frontend', 'Creator By'),
                'created_at' => Yii::t('frontend', 'Created At'),
                'updated_at' => Yii::t('frontend', 'Last modified'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReport()
    {
        return $this->hasOne(Report::className(), ['id' => 'report_id']);
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
