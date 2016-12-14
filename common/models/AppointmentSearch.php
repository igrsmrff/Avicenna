<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Appointment;

/**
 * AppointmentSearch represents the model behind the search form about `common\models\Appointment`.
 */
class AppointmentSearch extends Appointment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'status',
                'patient_id',
                'doctor_id',
                'nurse_id'
            ],
                'safe'],
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
        $query = Appointment::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'date' => SORT_ASC, 'time' => SORT_ASC]]
        ]);

        $this->load($params);
        $query->joinWith('patient',false);
        $query->joinWith('doctor',false);
        $query->joinWith('nurse',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([ Appointment::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', Patient::tableName() . '.name', $this->patient_id])
            ->andFilterWhere(['like', Doctor::tableName() . '.name', $this->doctor_id])
            ->andFilterWhere(['like', Nurse::tableName() . '.name', $this->nurse_id])
            ->andFilterWhere(['like', Appointment::tableName() . '.date', $this->date]);
        ;

        $dataProvider->sort->attributes['patient'] = [
            'asc' => [ Patient::tableName().'.name' => SORT_ASC ],
            'desc' => [ Patient::tableName().'.name' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['date'] = [
            'asc' => [ Appointment::tableName().'.date' => SORT_ASC ],
            'desc' => [ Appointment::tableName().'.date' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
