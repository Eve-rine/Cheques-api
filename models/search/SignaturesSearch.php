<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Signatures;

/**
 * SignaturesSearch represents the model behind the search form of `app\models\Signatures`.
 */
class SignaturesSearch extends Signatures
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sig_id', 'account_id', 'signatory_one', 'signatory_two', 'signatory_three'], 'integer'],
            [['min_amount', 'max_amount'], 'number'],
            [['type_one', 'type_two', 'type_three','status','created_by','created_at','updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Signatures::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'sig_id' => $this->sig_id,
            'account_id' => $this->account_id,
            'min_amount' => $this->min_amount,
            'max_amount' => $this->max_amount,
            'signatory_one' => $this->signatory_one,
            'signatory_two' => $this->signatory_two,
            'signatory_three' => $this->signatory_three,
        ]);

        $query->andFilterWhere(['like', 'type_one', $this->type_one])
            ->andFilterWhere(['like', 'type_two', $this->type_two])
            ->andFilterWhere(['like', 'type_three', $this->type_three]);

        return $dataProvider;
    }
}
