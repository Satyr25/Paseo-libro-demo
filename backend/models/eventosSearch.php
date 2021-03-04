<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Eventos;

/**
 * eventosSearch represents the model behind the search form of `backend\models\Eventos`.
 */
class eventosSearch extends Eventos
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
        $query = Eventos::find()
            ->select('eventos.*')
            ->orderBy(['id' => SORT_DESC]);
//            ->join('INNER JOIN', 'categoria', 'eventos.categoria_id = categoria.id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
//        var_dump($query->createCommand()->getRawSql());exit;
//        echo '<br>';
        if($params['eventosSearch']['nombre']){
            $query->andFilterWhere(['like', 'eventos.nombre', $params['eventosSearch']['nombre']])
            ->orFilterWhere(['like','eventos.tema', $params['eventosSearch']['nombre']])
            ->orFilterWhere(['like','eventos.presentador', $params['eventosSearch']['nombre']]);
        }

        return $dataProvider;
    }
}
