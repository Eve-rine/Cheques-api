<?php

namespace app\models\search;

use app\models\Banks;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BanksSearch extends Banks
{
    public function rules()
    {
        return [
            [['bank_id'], 'integer'],
            [['bank_name','bank_code','head_office_number','head_office_address','head_office_email','status','created_at','updated_at','created_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Banks::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'bank_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'bank_id' => $this->bank_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->bank_name])
            ->andFilterWhere(['like', 'bank_code', $this->bank_code])
            ->andFilterWhere(['like', 'head_office_number', $this->head_office_number])
            ->andFilterWhere(['like', 'head_office_address', $this->head_office_address])
            ->andFilterWhere(['like', 'head_office_email', $this->head_office_email])
            ->andFilterWhere(['like', 'head_office_number', $this->head_office_number])
               ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);
            

        return $dataProvider;
    }
}
