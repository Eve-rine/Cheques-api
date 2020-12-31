<?php

namespace app\models\search;

use app\models\Signatories;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SignatoriesSearch extends signatories
{
    public function rules()
    {
        return [
            [['signatory_id','user_id', 'account_id'], 'integer'],
            [['type','status','created_by','created_at','updated_at'], 'safe'],
        ];

    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Signatories::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'signatory_id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->signatory_id,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
