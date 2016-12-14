<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * DoctorSearch represents the model behind the search form about `common\models\Doctor`.
 */
class DoctorSearch extends Doctor
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
                    'profile',
                    'department_id',
                    'username',
                    'email'
                ], 'safe'
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
        $query = Doctor::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [ 'id' => 'DESC']]
        ]);

        $this->load($params);
        //model relation
        $query->joinWith('department',false);
        $query->joinWith('user',false);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query
            ->andFilterWhere([Doctor::tableName() .'.id' => $this->id])
            ->andFilterWhere(['like', User::tableName() . '.email', $this->email])
            ->andFilterWhere(['like', User::tableName() . '.username', $this->username])
            ->andFilterWhere(['like', Doctor::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', Doctor::tableName() . '.address', $this->address])
            ->andFilterWhere(['like', Doctor::tableName() . '.phone', $this->phone])
            ->andFilterWhere(['like', Doctor::tableName() . '.profile', $this->profile])
            ->andFilterWhere(['like', Department::tableName() . '.title', $this->department_id])
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

