<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Admin;

/**
 * AdminSearch represents the model behind the search form about `common\models\Admin`.
 */
class AdminSearch extends Admin
{
    public $username;
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'updated_at'], 'integer'],

            [
                [
                    'system_name',
                    'system_title',
                    'name',
                    'address',
                    'paypal_email',
                    'currency',
                    'phone',
                    'system_email',
                    'adminImageUrl',
                    'language',
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
        $query = Admin::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'system_name', $this->system_name])
            ->andFilterWhere(['like', 'system_title', $this->system_title])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'paypal_email', $this->paypal_email])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'system_email', $this->system_email])
            ->andFilterWhere(['like', 'adminImageUrl', $this->adminImageUrl])
            ->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', User::tableName() . '.email', $this->email])
            ->andFilterWhere(['like', User::tableName() . '.username', $this->username]);

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
