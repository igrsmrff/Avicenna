<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sms;

/**
 * SmsSearch represents the model behind the search form about `common\models\Sms`.
 */
class SmsSearch extends Sms
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'appointment_id'], 'integer'],
            [['text_message', 'status'], 'safe'],
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
        $query = Sms::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => SORT_DESC]]
        ]);

        $this->load($params);
        $query->joinWith('appointment',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query
            ->andFilterWhere([ Sms::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', Sms::tableName() . '.text_message', $this->text_message])
            ->andFilterWhere(['like', Sms::tableName() . '.status', $this->status])
            ->andFilterWhere([ Appointment::tableName() . '.id' => $this->appointment_id]);

        $dataProvider->sort->attributes['creator_id'] = [
            'asc' => [ Sms::tableName() . '.creator_id' => SORT_ASC ],
            'desc' => [ Sms::tableName() . '.creator_id' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
