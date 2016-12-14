<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Report;

/**
 * ReportSearch represents the model behind the search form about `common\models\Report`.
 */
class ReportSearch extends Report
{
    public $doctor_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id'], 'integer'],
            [
                [
                    'title',
                    'description',
                    'doctor_name'
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
        $query = Report::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('appointment.doctor', true);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['like', Doctor::tableName() . '.name' , $this->doctor_name])
            ->andFilterWhere([Report::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', Report::tableName() . '.title', $this->title])
            ->andFilterWhere(['like', Report::tableName() . '.description', $this->description])
            ->andFilterWhere([Appointment::tableName() . '.id' => $this->appointment_id]);

        return $dataProvider;
    }
}
