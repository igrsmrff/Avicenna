<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DoctorPatientPivot;

/**
 * DoctorPatientSerach represents the model behind the search form about `common\models\DoctorPatientPivot`.
 */
class DoctorPatientSerach extends DoctorPatientPivot
{
    public $name;
    public $address;
    public $phone;
    public $sex;
    public $marital_status;
    public $insurance_company;
    public $insurance_number;
    public $insurance_expiration_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctor_id', 'patient_id'], 'integer'],
            [[
                'name',
                'address',
                'phone',
                'sex',
                'marital_status',
                'insurance_company',
                'insurance_number',
            ], 'safe'],
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
        $query = DoctorPatientPivot::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('doctor', false);
        $query->joinWith('patient', true);
        $query->joinWith('patient.insuranceCompany', true);


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
            ->andFilterWhere(['like', Patient::tableName() . '.insurance_number', $this->insurance_number])
            ->andFilterWhere(['like', Patient::tableName() . '.insurance_expiration_date', $this->insurance_expiration_date])
            ->andFilterWhere(['like', Patient::tableName() . '.marital_status', $this->marital_status])
            ->andFilterWhere([InsuranceCompany::tableName() . '.id'=> $this->insurance_company])
            ->andFilterWhere([Doctor::tableName() . '.id'=> $this->doctor_id]);


        $dataProvider->sort->attributes['id'] = [
            'asc' => [ Patient::tableName() . '.id' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.id' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['name'] = [
            'asc' => [ Patient::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.name' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['address'] = [
            'asc' => [ Patient::tableName() . '.address' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.address' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['phone'] = [
            'asc' => [ Patient::tableName() . '.phone' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.phone' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['sex'] = [
            'asc' => [ Patient::tableName() . '.sex' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.sex' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['marital_status'] = [
            'asc' => [ Patient::tableName() . '.marital_status' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.marital_status' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['insurance_company'] = [
            'asc' => [ Patient::tableName() . '.insurance_company' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.insurance_company' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['insurance_number'] = [
            'asc' => [ Patient::tableName() . '.insurance_number' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.insurance_number' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['insurance_expiration_date'] = [
            'asc' => [ Patient::tableName() . '.insurance_expiration_date' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.insurance_expiration_date' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
