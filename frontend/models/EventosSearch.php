<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Eventos;

/**
 * EventosSearch represents the model behind the search form of `frontend\models\Eventos`.
 */
class EventosSearch extends Eventos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fecha', 'categoria_id'], 'integer'],
            [['nombre', 'tema', 'presentador', 'hora', 'imagen', 'descripcion'], 'safe'],
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
        $query = Eventos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if($params['categoria']){
            $query->andWhere(['categoria_id' => $params['categoria']]);
        }
        return $dataProvider;
    }
}
