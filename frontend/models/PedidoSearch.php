<?php
namespace frontend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\PedidoLibro;
use frontend\models\Libro;
use frontend\models\LibroPedido;
use frontend\models\LibroAutor;
use frontend\models\Autor;
use frontend\models\Clientes;
use frontend\models\DatosPago;
use frontend\models\PagoTienda;


class PedidoSearch extends PedidoLibro {
    
    public $titulo;

    public function rules()
    {
        return [
            [['titulo',], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'titulo' => 'Titulo',
        ];
    }

    public function search($params)
    {
        $query = PedidoSearch::find()
        ->select('pedido_libro.*, 
            libro_pedido.cantidad as cantidad, 
            clientes.nombre as nombre, 
            clientes.apellidos as apellidos, 
            estado_pedido.nombre as estado')
        ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.pedido_libro_id = pedido_libro.id')
        ->join('INNER JOIN', 'clientes', 'clientes.id = pedido_libro.clientes_id')
        ->join('INNER JOIN', 'estado_pedido', 'estado_pedido.id = pedido_libro.estado_pedido_id')
        ->orderBy(['created_at' => SORT_DESC,]);
      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if($params['PedidoSearch']['titulo']){
            $query->andFilterWhere(['like', 'pedido_libro.numero_pedido', $params['PedidoSearch']['titulo']])
            ->orFilterWhere(['like','pedido_libro.costo_total', $params['PedidoSearch']['titulo']])
            ->orFilterWhere(['like','estado_pedido.nombre', $params['PedidoSearch']['titulo']])
            ->orFilterWhere(['like','clientes.nombre', $params['PedidoSearch']['titulo']])
            ;
        }
        

        return $dataProvider;
    }
    public function buscar($id)
    {
        $query = LibroPedido::find()
        ->select('libro_pedido.*, 
            imagenes.portada AS portada, 
            libro.titulo AS nombre, 
            autor.nombre AS autor, 
            libro.promo AS promo, 
            libro.pvp AS pvp')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro_pedido.pedido_libro_id='.$id)
        ->all();
        return $query;
    }

    public function buscarPedidos($id)
    {
        $query = LibroPedido::find()
        ->select('libro_pedido.*, imagenes.portada AS portada, libro.titulo AS nombre, autor.nombre AS autor, libro.promo AS promo, libro.pvp AS pvp')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro_pedido.pedido_libro_id='.$id)->all();
        return $query;
    }
    public function clientes($id)
    {
        $query = Clientes::find()
        ->where('clientes.id='.$id)->one();
        return $query;
    }
    public function datosPago($id)
    {
        if($id){
            $query = DatosPago::find()
            ->where('datos_pago.id='.$id)->one();
            return $query;
        }else{
            return NULL;
        }
        
    }
    public function pagoTienda($id)
    {
        if($id){
            $query = PagoTienda::find()
                ->where('pago_tienda.id='.$id)->one();
                return $query;
        }else{
            return NULL;
        }
    }
    public function getCantidad($id)
    {
        $total = 0;
        $query = LibroPedido::find()
        ->where('pedido_libro_id='.$id)->all();
        foreach ($query as $key => $value) {
            $total = $value->cantidad+$total;
        }
        return $total;
    }
    
    public function pedidoCliente(){
        
        $cliente = Clientes::find()->where(['usuario_id' => Yii::$app->user->identity->id])->one();
        
        $query = PedidoSearch::find()
        ->select('pedido_libro.*, 
            libro_pedido.cantidad as cantidad, 
            clientes.nombre as nombre, 
            clientes.apellidos as apellidos, 
            estado_pedido.nombre as estado')
        ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.pedido_libro_id = pedido_libro.id')
        ->join('INNER JOIN', 'clientes', 'clientes.id = pedido_libro.clientes_id')
        ->join('INNER JOIN', 'estado_pedido', 'estado_pedido.id = pedido_libro.estado_pedido_id')
        ->orderBy(['created_at' => SORT_DESC,]);
//        ->where(['pedido_libro.clientes_id' => $cliente->id]); 
//        var_dump(Yii::$app->user->identity->id);
//        var_dump($query->createCommand()->getRawSql());exit;
      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        $query->where(['pedido_libro.clientes_id' => $cliente->id]);        

        return $dataProvider;
    }
}
?>
