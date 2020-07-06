<?php

namespace backend\models\support;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\support\Faq;

/**
 * FaqSearch represents the model behind the search form of `backend\models\cms\Faq`.
 */
class FaqSearch extends Faq
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'faq_group_id', 'status'], 'integer'],
            [['created_at','question', 'answer'], 'safe'],
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
        $query = Faq::find();

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

        $query->leftJoin('faq_lang','faq.id = faq_lang.faq_id AND faq_lang.language=\''.Yii::$app->language.'\'');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'faq_group_id' => $this->faq_group_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'faq_lang.question', $this->question])
            ->andFilterWhere(['like', 'faq_lang.answer', $this->answer]);

        $query->andFilterWhere(['DATE(created_at)' => $this->created_at]);

        return $dataProvider;
    }
}
