<?php

namespace app\models\search;

use app\models\Batch;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BatchSearch extends Batch
{
    public function rules()
    {
        return [
            [['batch_id','cheques','amount','status','bank_id','branch_id','account_id','created_at','updated_at','created_by'], 'string'],
            [['id','batch_id','cheques','amount','status','bank_id','branch_id','account_id','created_at','updated_at','created_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Batch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'batch_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->batch_id,
        ]);

        $query  ->andFilterWhere(['like', 'batch_id', $this->batch_id])
                ->andFilterWhere(['like', 'cheques', $this->cheques])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'bank_id', $this->status])
                ->andFilterWhere(['like', 'branch_id', $this->status])
                ->andFilterWhere(['like', 'account_id', $this->status])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
