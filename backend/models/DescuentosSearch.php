<?php
namespace backend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Descuentos;


class DescuentosSearch extends Descuentos {

    public function rules()
    {
        return [
            [['codigo'], 'required'],
            [['activo', 'global'], 'integer'],
            [['procentaje'], 'number'],
            [['codigo'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return[
            'codigo' => 'Codigo',
            'activo' => 'Activo',
            'porcentaje' => 'Porcentaje',
            'global' => 'Cupón Global'
        ];
    }

    public function search($params)
    {
        $query = Descuentos::find();
      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if($params['DescuentosSearch']['codigo']){
            $query->andFilterWhere(['like', 'descuentos.codigo', $params['DescuentosSearch']['codigo']])
            ;
            if($params['DescuentosSearch']['codigo'] == 'Activo' || $params['DescuentosSearch']['codigo'] == 'activo'){
                $query->orFilterWhere(['like','descuentos.activo', 1]);
            }elseif ($params['DescuentosSearch']['codigo'] == 'Inactivo' || $params['DescuentosSearch']['codigo'] == 'inactivo') {
                $query->orFilterWhere(['like','descuentos.activo', 0]);
            }elseif (strpos($params['DescuentosSearch']['codigo'], '%')) {
                $para = rtrim($params['DescuentosSearch']['codigo'], "%");
                $query->orFilterWhere(['like','descuentos.porcentaje', $para]);
            }
        }
    
        

        return $dataProvider;
    }
    public function buscar($id)
    {
        // $query = LibroPedido::find()
        // ->select('libro_pedido.*, imagenes.portada AS portada, libro.titulo AS nombre, autor.nombre AS autor')
        // ->join('INNER JOIN', 'imagenes', 'imagenes.libro_id = libro_pedido.libro_id')
        // ->join('INNER JOIN', 'libro', 'libro.id = libro_pedido.libro_id')
        // ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        // ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        // ->where('libro_pedido.pedido_libro_id='.$id)->all();
        // return $query;
    }
}
?>
