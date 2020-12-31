<?php

namespace app\models\search;

use app\models\Regions;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RegionsSearch extends Regions
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'location','supervisor'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Regions::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'supervisor', $this->supervisor]);

        return $dataProvider;
    }
}
