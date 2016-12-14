<?php

namespace common\models;

use common\models\Payment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * PaymentSearch represents the model behind the search form about `common\models\Payment`.
 */
class PaymentSearch extends Payment
{
    public $invoice_number;
    public $total_invoice_amount;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['payment_method', 'invoice_id', 'invoice_number', 'total_invoice_amount'], 'safe'],
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
        $query = Payment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        $query->joinWith('invoice',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query
            ->andFilterWhere([ Payment::tableName() . '.id' => $this->id])
            ->andFilterWhere(['like', Invoice::tableName() . '.invoice_number', $this->invoice_number])
            ->andFilterWhere(['like', Invoice::tableName() . '.total_invoice_amount', $this->total_invoice_amount])
            ->andFilterWhere(['like', Payment::tableName() . '.payment_method', $this->payment_method])
        ;

        $dataProvider->sort->attributes['invoice_number'] = [
            'asc' => [ Invoice::tableName().'.invoice_number' => SORT_ASC ],
            'desc' => [ Invoice::tableName().'.invoice_number' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['total_invoice_amount'] = [
            'asc' => [ Payment::tableName() . '.total_invoice_amount' => SORT_ASC ],
            'desc' => [ Payment::tableName() . '.total_invoice_amount' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
