<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form about `common\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    public $doctor_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'appointment_id'
                ]
                , 'integer'],

            [
                [
                    'id',
                    'appointment_id',
                    'doctor_id',
                    'description',
                    'invoice_number',
                    'due_date',
                    'status',
                    'vat_percentage',
                    'invoice_entries',
                    'discount_amount',
                    'total_invoice_amount',
                    'sub_total_amount'
                ]
                , 'safe'],
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
        $query = Invoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('appointment', true);
        $query->joinWith('appointment.doctor', true);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([ Doctor::tableName() . '.id' => $this->doctor_id])
            ->andFilterWhere([ Invoice::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', 'invoice_number', $this->invoice_number])
            ->andFilterWhere(['like', 'due_date', $this->due_date])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'discount_amount', $this->discount_amount])
            ->andFilterWhere(['like', 'sub_total_amount', $this->sub_total_amount])
            ->andFilterWhere(['like', 'total_invoice_amount', $this->total_invoice_amount])
            ->andFilterWhere([Appointment::tableName() . '.id' => $this->appointment_id])
        ;

        $dataProvider->sort->attributes['appointment_id'] = [
            'asc' => [ Appointment::tableName().'.id' => SORT_ASC ],
            'desc' => [ Appointment::tableName().'.id' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
