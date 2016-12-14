<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notices}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property string $updated_at
 * @property string $created_at
 */
class Notice extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['description'], 'string'],
                [['start_date', 'end_date'], 'required'],
                [['start_date', 'end_date', 'updated_at', 'created_at'], 'safe'],
                [['title'], 'string', 'max' => 255],
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
                'start_date' => Yii::t('common', 'Start Date'),
                'end_date' => Yii::t('common', 'End Date'),
                'updated_at' => Yii::t('common', 'Updated At'),
                'created_at' => Yii::t('common', 'Created At'),
            ]
        );
    }
}
