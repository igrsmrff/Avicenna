<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Patient;

/**
 * PatientSearch represents the model behind the search form about `common\models\Patient`.
 */
class PatientSearch extends Patient
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'insurance_company_id',
                    'sex',
                    'marital_status',
                    'insurance_number'
                ],
                'integer'
            ],

            [['name', 'address', 'phone', 'patientImageUrl'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Patient::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('insuranceCompany',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([Patient::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', Patient::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', Patient::tableName() . '.address', $this->address])
            ->andFilterWhere(['like', Patient::tableName() . '.phone', $this->phone])
            ->andFilterWhere(['like', Patient::tableName() . '.sex', $this->sex])
            ->andFilterWhere(['like', Patient::tableName() . '.marital_status', $this->marital_status])
            ->andFilterWhere(['like', Patient::tableName() . '.insurance_expiration_date', $this->insurance_expiration_date])
            ->andFilterWhere(['like', Patient::tableName() . '.insurance_number', $this->insurance_number])
            ->andFilterWhere([InsuranceCompany::tableName() . '.id' => $this->insurance_company_id])
 ;

        return $dataProvider;
    }
}
