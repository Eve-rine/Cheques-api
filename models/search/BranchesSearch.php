<?php

namespace app\models\search;

use app\models\Branches;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BranchesSearch extends Branches
{
    public function rules()
    {
        return [
            [['bank_id','branch_id'], 'integer'],
            [['branch_name', 'physical_address','contact_person_name','contact_person_number','contact_person_email','status','created_by','created_at','updated_at'] ,'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Branches::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'branch_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'branch_name', $this->branch_name])
        ->andFilterWhere(['like', 'bank_id', $this->bank_id])
            ->andFilterWhere(['like', 'physical_address', $this->physical_address])
             ->andFilterWhere(['like', 'contact_person_name', $this->contact_person_name])
              ->andFilterWhere(['like', 'contact_person_number', $this->contact_person_number])
               ->andFilterWhere(['like', 'contact_person_email', $this->contact_person_email])
                ->andFilterWhere(['like', 'status', $this->status])
                 ->andFilterWhere(['like', 'created_by', $this->created_by])
                  ->andFilterWhere(['like', 'created_at', $this->created_at])
                   ->andFilterWhere(['like', 'updated_at', $this->updated_at]);
            
            

        return $dataProvider;
    }
}
