<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%invoice_entry_drop_down}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $created_at
 * @property integer $updated_at
 */
class InvoiceEntryDropDown extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%invoice_entry_drop_down}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['title'], 'required'],
                [['created_at', 'updated_at'], 'integer'],
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
                'created_at' => Yii::t('frontend', 'Created At'),
                'updated_at' => Yii::t('frontend', 'Last modified'),
            ]
        );
    }

    public static function getAllEntriesListDropDown()
    {
        $entriesDropDown = [];
        $entries = self::find()->asArray()->all();
        if(count($entries)) {
            foreach ($entries as $entry) {
                $entriesDropDown[$entry['id']] = $entry['title'];
            }
        }
        return $entriesDropDown;
    }
}
