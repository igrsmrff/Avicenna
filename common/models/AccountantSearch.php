<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Accountant;
/**
 * AccountantSearch represents the model behind the search form about `common\models\Accountant`.
 */
class AccountantSearch extends Accountant
{
    public $username;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                [
                    'name',
                    'phone',
                    'address',
                    'accountantImageUrl',
                    'username',
                    'email'
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
        $query = Accountant::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        //model relation
        $query->joinWith('user',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([Accountant::tableName() .'.id' => $this->id])
            ->andFilterWhere(['like', User::tableName() . '.email', $this->email])
            ->andFilterWhere(['like', User::tableName() . '.username', $this->username])
            ->andFilterWhere(['like', Accountant::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', Accountant::tableName() . '.address', $this->address])
            ->andFilterWhere(['like', Accountant::tableName() . '.phone', $this->phone])
        ;

        $dataProvider->sort->attributes['username'] = [
            'asc' => [ User::tableName().'.username' => SORT_ASC ],
            'desc' => [ User::tableName().'.username' => SORT_DESC  ],
        ];

        $dataProvider->sort->attributes['email'] = [
            'asc' => [ User::tableName().'.email' => SORT_ASC ],
            'desc' => [ User::tableName().'.email' => SORT_DESC  ],
        ];

        return $dataProvider;
    }
}
