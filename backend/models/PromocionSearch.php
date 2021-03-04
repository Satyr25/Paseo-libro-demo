<?php
namespace backend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\LibroPromocion;
use backend\models\Promocion;
use backend\models\Libro;
use backend\models\LibroAutor;
use backend\models\Autor;


class PromocionSearch extends LibroPromocion {
    
    public $codigo;
    public $titulo;

    public function rules()
    {
        return [
            [['codigo',], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'codigo' => 'Codigo',
        ];
    }

    public function search($params)
    {
        $query = Promocion::find()
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if($params['PromocionSearch']['codigo']){
            $query->andFilterWhere(['like', 'promocion.codigo', $params['PromocionSearch']['codigo']])
            ;
        }
        

        return $dataProvider;
    }
}
?>
