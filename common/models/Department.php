<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%departments}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $updated_at
 * @property string $created_at
 *
 * @property Doctor[] $doctors
 * @property Nurse[] $nurses
 */
class Department extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%departments}}';
    }

    public static function getDepartmentsDropDown()
    {
        $departmentsDropDown = [];
        $data = self::find()->asArray()->all();
        if(count($data)>0) {
            foreach ($data as $key => $value) {
                $departmentsDropDown[$value['id']] = $value['title'];
            }
        }
        return $departmentsDropDown;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['title', 'description'], 'required' ],
                [['updated_at', 'created_at'], 'safe'],
                [['title', 'description'], 'string', 'max' => 255],
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
                'title' => Yii::t('common', 'Title'),
                'description' => Yii::t('common', 'Description'),
                'updated_at' => Yii::t('common', 'Updated At'),
                'created_at' => Yii::t('common', 'Created At'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoctors()
    {
        return $this->hasMany(Doctors::className(), ['department_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNurses()
    {
        return $this->hasMany(Nurses::className(), ['department_id' => 'id']);
    }
}
