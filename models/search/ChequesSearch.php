<?php

namespace app\models\search;

use app\models\Cheques;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class ChequesSearch extends Cheques
{
    public function rules()
    {
        return [
            [['cheque_id','batch_id','payee','amount','pay_date','status','bank_id','branch_id','cheque_type','account_id','created_at','updated_at','created_by'], 'string'],
            [['cheque_id','batch_id','payee','amount','pay_date','status','bank_id','branch_id','cheque_type','account_id','created_at','updated_at','created_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Cheques::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'cheque_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'cheque_id' => $this->cheque_id,
        ]);

        $query->andFilterWhere(['like', 'cheque_id', $this->cheque_id])
                ->andFilterWhere(['like', 'batch_id', $this->batch_id])
                ->andFilterWhere(['like', 'payee', $this->payee])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'pay_date', $this->pay_date])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'bank_id', $this->status])
                ->andFilterWhere(['like', 'branch_id', $this->status])
                ->andFilterWhere(['like', 'cheque_type', $this->cheque_type])
                ->andFilterWhere(['like', 'account_id', $this->status])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
