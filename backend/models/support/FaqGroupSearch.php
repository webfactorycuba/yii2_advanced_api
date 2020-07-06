<?php

namespace backend\models\support;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\support\FaqGroup;

/**
 * FaqGroupSearch represents the model behind the search form of `backend\models\nomenclators\FaqGroup`.
 */
class FaqGroupSearch extends FaqGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = FaqGroup::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->leftJoin('faq_group_lang','faq_group.id = faq_group_lang.faq_group_id AND faq_group_lang.language=\''.Yii::$app->language.'\'');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query
            ->andFilterWhere(['like', 'faq_group_lang.name', $this->name])
            ->andFilterWhere(['like', 'faq_group_lang.description', $this->description])
        ;

        return $dataProvider;
    }
}
