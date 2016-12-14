<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * InitialinspectionSearch represents the model behind the search form about `common\models\Initialinspection`.
 */
class InitialinspectionSearch extends Initialinspection
{
    public $creator_id;
    public $doctor_name;
    public $patient_name;
    public $nurse_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','appointment_id'], 'integer'],

            [
                [
                    'weight',
                    'height',
                    'blood_pressure',
                    'temperature',
                    'notes',
                    'patient_name',
                    'doctor_name',
                    'nurse_name'
                ],
                'safe'
            ],
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
        $query = Initialinspection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('appointment.patient', true);
        $query->joinWith('appointment.doctor', true);
        $query->joinWith('appointment.nurse', true);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', Doctor::tableName() . '.name' , $this->doctor_name])
            ->andFilterWhere(['like', Patient::tableName() . '.name', $this->patient_name])
            ->andFilterWhere(['like', Nurse::tableName() . '.name', $this->nurse_name])
            ->andFilterWhere([Initialinspection::tableName() . '.id' => $this->id])
            ->andFilterWhere([Appointment::tableName() . '.id' => $this->appointment_id])
            ->andFilterWhere(['like', Initialinspection::tableName() . '.weight', $this->weight])
            ->andFilterWhere(['like', Initialinspection::tableName() . '.height', $this->height])
            ->andFilterWhere(['like', Initialinspection::tableName() . '.blood_pressure', $this->blood_pressure])
            ->andFilterWhere(['like', Initialinspection::tableName() . '.temperature', $this->temperature])
            ->andFilterWhere(['like', Initialinspection::tableName() . '.notes', $this->notes]);

        $dataProvider->sort->attributes['creator_id'] = [
            'asc' => [ Initialinspection::tableName() . '.creator_id' => SORT_ASC ],
            'desc' => [ Initialinspection::tableName() . '.creator_id' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['patient_name'] = [
            'asc' => [ Patient::tableName() . '.name' => SORT_ASC ],
            'desc' => [ Patient::tableName() . '.name' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
