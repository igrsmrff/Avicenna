<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%insurance_companies}}".
 *
 * @property integer $id
 * @property string $company_title
 * @property string $company_description
 * @property string $companyImageUrl
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Patients[] $patients
 */
class InsuranceCompany extends \common\overrides\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurance_companies}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            [
                [['company_title'], 'required'],
                [['created_at', 'updated_at'], 'integer'],
                [['company_title', 'company_description', 'companyImageUrl'], 'string', 'max' => 255],
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
                'company_title' => Yii::t('common', 'Company Title'),
                'company_description' => Yii::t('common', 'Company Description'),
                'companyImageUrl' => Yii::t('common', 'Company Image Url'),
                'created_at' => Yii::t('common', 'Created At'),
                'updated_at' => Yii::t('common', 'Updated At'),
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPatients()
    {
        return $this->hasMany(Patients::className(), ['insurance_company_id' => 'id']);
    }

    public static function getInsuranceCompanyListDropDown()
    {
        $insuranceCompany = [];
        $data = self::find()->asArray()->all();
        if(count($data)) {
            foreach ($data as $key => $value) {
                $insuranceCompany[$value['id']] = $value['company_title'];
            }
        }
        return $insuranceCompany;
    }
}
