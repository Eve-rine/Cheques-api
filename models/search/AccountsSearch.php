<?php

namespace app\models\search;

use app\models\Accounts;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class AccountsSearch extends Accounts
{
    public function rules()
    {
        return [
            [['bank_id','branch_id','account_id'], 'integer'],
            [['bank_id','branch_id','account_name','account_number','kra_pin','minimum_signatories','status','created_by','created_at','updated_at'],'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Accounts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'account_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'account_id' => $this->account_id,
        ]);

 
        $query->andFilterWhere(['like', 'account_name',$this->account_name] )
                 ->andFilterWhere(['like', 'bank_id', $this->bank_id])
                ->andFilterWhere(['like', 'account_number', $this->account_number])
                 ->andFilterWhere(['like', 'kra_pin', $this->kra_pin])
                  ->andFilterWhere(['like', 'status', $this->status])
                   ->andFilterWhere(['like', 'created_at', $this->created_at])
                      ->andFilterWhere(['like', 'updated_at',$this->updated_at])
                       ->andFilterWhere(['like', 'created_by', $this->created_by]);
            
            

        return $dataProvider;
    }
}
