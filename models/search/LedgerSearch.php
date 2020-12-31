<?php

namespace app\models\search;

use app\models\Ledger;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LedgerSearch extends Ledger
{
    public function rules()
    {
        return [
            [['ledger_id','transaction_date','value_date','naration','value_date','amount_out','amount_in','amount','closing_balance','running_balance','ledger_type','start_month','end_month','status','created_date','created_at','updated_at','created_by'], 'string'],
            [['ledger_id','transaction_date','value_date','naration','value_date','amount_out','amount_in','amount','closing_balance','running_balance','ledger_type','start_month','end_month','status','created_date','created_at','updated_at','created_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Ledger::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'transaction_date' => SORT_ASC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ledger_id' => $this->ledger_id,
        ]);

        $query->andFilterWhere(['like', 'ledger_id', $this->ledger_id])
                ->andFilterWhere(['like', 'transaction_date', $this->transaction_date])
                ->andFilterWhere(['like', 'value_date', $this->value_date])
                ->andFilterWhere(['like', 'naration', $this->naration])
                ->andFilterWhere(['like', 'value_date', $this->value_date])
                ->andFilterWhere(['like', 'amount_out', $this->amount_out])
                ->andFilterWhere(['like', 'amount_in', $this->amount_in])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'closing_balance', $this->closing_balance])
                ->andFilterWhere(['like', 'running_balance', $this->running_balance])
                ->andFilterWhere(['like', 'ledger_type', $this->ledger_type])
                ->andFilterWhere(['like', 'month_year', $this->month_year])
                ->andFilterWhere(['like', 'start_month', $this->start_month])
                ->andFilterWhere(['like', 'end_month', $this->end_month])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'created_date', $this->created_date])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
