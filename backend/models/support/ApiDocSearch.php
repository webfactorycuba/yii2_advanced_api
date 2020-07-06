<?php

namespace backend\models\support;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\support\ApiDoc;

/**
 * ApiDocSearch represents the model behind the search form of `backend\models\business\ApiDoc`.
 */
class ApiDocSearch extends ApiDoc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'get', 'post', 'put', 'delete', 'options', 'status'], 'integer'],
            [['name', 'created_at', 'updated_at', 'type'], 'safe'],
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
        $query = ApiDoc::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        $query->leftJoin('api_doc_lang','api_doc.id = api_doc_lang.api_doc_id AND api_doc_lang.language=\''.Yii::$app->language.'\'');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'get' => $this->get,
            'post' => $this->post,
            'put' => $this->put,
            'delete' => $this->delete,
            'options' => $this->options,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'api_doc.name', $this->name])
            ->andFilterWhere(['like', 'type', $this->type])
        ;

        return $dataProvider;
    }
}
