<?php

namespace backend\models\support;

use common\models\GlobalFunctions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\support\CronjobLog;

/**
 * CronjobLogSearch represents the model behind the search form of `backend\models\support\CronjobLog`.
 */
class CronjobLogSearch extends CronjobLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cronjob_task_id'], 'integer'],
            [['message', 'execution_date', 'created_at'], 'safe'],
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
        $query = CronjobLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        $this->load($params);

        // descomenta y utiliza tu relación con las traducciones para poder cargar los atributos de traducción
        // $query->leftJoin('table_lang',"table.id = table_lang.table_id AND table_lang.language='".Yii::$app->language."'");

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cronjob_task_id' => $this->cronjob_task_id,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        if(isset($this->execution_date) && !empty($this->execution_date))
        {
            $date_explode = explode(' - ',$this->execution_date);
            $start_date = GlobalFunctions::formatDateToSaveInDB($date_explode[0]).' 00:00:00';
            $end_date = GlobalFunctions::formatDateToSaveInDB($date_explode[1]).' 23:59:59';

            $query->andFilterWhere(['>=', 'execution_date', $start_date])
                ->andFilterWhere(['<=', 'execution_date', $end_date]);

            $this->execution_date = null;
        }

        $query->orderBy('execution_date DESC');

        return $dataProvider;
    }
}
