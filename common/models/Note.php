<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%notes}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $text
 * @property integer $user_id
 * @property string $color
 * @property string $updated_at
 * @property string $created_at
 *
 * @property User $user
 */
class Note extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['text'], 'string'],
                [['user_id'], 'integer'],
                [['updated_at', 'created_at'], 'safe'],
                [['title', 'color'], 'string', 'max' => 255],
                [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
                'text' => Yii::t('common', 'Text'),
                'user_id' => Yii::t('common', 'User ID'),
                'color' => Yii::t('common', 'Color'),
                'updated_at' => Yii::t('common', 'Updated At'),
                'created_at' => Yii::t('common', 'Created At'),
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
