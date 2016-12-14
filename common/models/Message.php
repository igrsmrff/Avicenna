<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%messages}}".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $text_message
 * @property integer $status
 * @property string $updated_at
 * @property string $created_at
 *
 * @property User $receiver
 * @property User $sender
 */
class Message extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['sender_id', 'receiver_id', 'status'], 'integer'],
                [['text_message'], 'string'],
                [['updated_at', 'created_at'], 'safe'],
                [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
                [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
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
                'sender_id' => Yii::t('common', 'Sender ID'),
                'receiver_id' => Yii::t('common', 'Receiver ID'),
                'text_message' => Yii::t('common', 'Text Message'),
                'status' => Yii::t('common', 'Status'),
                'updated_at' => Yii::t('common', 'Updated At'),
                'created_at' => Yii::t('common', 'Created At'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
}
