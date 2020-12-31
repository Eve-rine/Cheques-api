<?php

namespace app\models\search;

use app\models\Events;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class EventsSearch extends Events
{
    public function rules()
    {
        return [
            [['id','title','start','end','dragBgColor','status','created_at','updated_at','created_by'], 'string'],
            [['id','title','start','end','dragBgColor','status','created_at','updated_at','created_by'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Events::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'start', $this->start])
                ->andFilterWhere(['like', 'end', $this->end])
                ->andFilterWhere(['like', 'dragBgColor', $this->dragBgColor])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'created_at', $this->created_at])
                ->andFilterWhere(['like', 'updated_at', $this->updated_at])
                ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
