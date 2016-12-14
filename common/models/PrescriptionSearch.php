<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Prescription;

/**
 * PrescriptionSearch represents the model behind the search form about `common\models\Prescription`.
 */
class PrescriptionSearch extends Prescription
{
    public $doctor_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['medication', 'note', 'report_id', 'doctor_id'], 'safe'],
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
        $query = Prescription::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith('report',true);
        $query->joinWith('report.appointment.doctor', true);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([Appointment::tableName() . '.doctor_id' => $this->doctor_id])
            ->andFilterWhere([Prescription::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', Report::tableName() . '.title', $this->report_id])
            ->andFilterWhere(['like', Prescription::tableName() . '.medication', $this->medication])
            ->andFilterWhere(['like', Prescription::tableName() . '.note', $this->note]);

        return $dataProvider;
    }
}
