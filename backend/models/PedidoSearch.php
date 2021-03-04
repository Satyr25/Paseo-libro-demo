<?php
namespace backend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PedidoLibro;
use backend\models\Libro;
use backend\models\LibroPedido;
use backend\models\LibroAutor;
use backend\models\Autor;
use backend\models\Clientes;
use backend\models\DatosPago;
use backend\models\PagoTienda;


class PedidoSearch extends PedidoLibro {

    public $titulo;
    public $total;

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
        ->join('INNER JOIN', 'libro', 'libro_pedido.libro_id = libro.id')
        ->orderBy(['created_at' => SORT_DESC,]);

        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if(Yii::$app->user->identity->rol_id == 2){
            $query->andWhere(['=', 'libro.editorial_id', Yii::$app->user->identity->editorial_id]);
        }
        if(Yii::$app->user->identity->rol_id == 1){
            if($params['PedidoSearch']['id']){
                $query->andWhere(['=', 'libro.editorial_id', $params['PedidoSearch']['id']]);
            }
        }
        if($params['PedidoSearch']['inicio'] && $params['PedidoSearch']['fin']){
            $inicio = strtotime($params['PedidoSearch']['inicio']);
            $fin = strtotime($params['PedidoSearch']['fin']);
            $query->andWhere(['between', 'pedido_libro.created_at', $inicio, $fin ]);
        }
        
        if($params['PedidoSearch']['titulo']){
            $query->andFilterWhere([
                'or',
                ['like', 'pedido_libro.numero_pedido', $params['PedidoSearch']['titulo']],
                ['like','pedido_libro.costo_total', $params['PedidoSearch']['titulo']],
                ['like','estado_pedido.nombre', $params['PedidoSearch']['titulo']],
                ['like','clientes.nombre', $params['PedidoSearch']['titulo']]
            ]);
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
            libro.promo AS promocion,
            libro.pvp AS preciovp')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_pedido.libro_id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro_pedido.pedido_libro_id='.$id);

        if(Yii::$app->user->identity->rol_id == 2){ //si el usuario conectado es admin de editorial
            $query->andWhere('libro.editorial_id = '.Yii::$app->user->identity->editorial_id);
        }
        return $query->all();
    }


    public function total($params){
        
        $query = PedidoSearch::find()
        ->select('SUM(libro_pedido.total) AS total')
        ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.pedido_libro_id = pedido_libro.id')
        ->join('INNER JOIN', 'clientes', 'clientes.id = pedido_libro.clientes_id')
        ->join('INNER JOIN', 'estado_pedido', 'estado_pedido.id = pedido_libro.estado_pedido_id')
        ->join('INNER JOIN', 'libro', 'libro_pedido.libro_id = libro.id')
        ->orderBy(['pedido_libro.created_at' => SORT_DESC,]);

        if(Yii::$app->user->identity->rol_id == 2){
            $query->andWhere(['=', 'libro.editorial_id', Yii::$app->user->identity->editorial_id]);
        }
        if(Yii::$app->user->identity->rol_id == 1){
            if($params['PedidoSearch']['id']){
                $query->andWhere(['=', 'libro.editorial_id', $params['PedidoSearch']['id']]);
            }
        }
        if($params['PedidoSearch']['inicio'] && $params['PedidoSearch']['fin']){
            $inicio = strtotime($params['PedidoSearch']['inicio']);
            $fin = strtotime($params['PedidoSearch']['fin']);
            $query->andWhere(['between', 'pedido_libro.created_at', $inicio, $fin ]);
        } else {
            $query->andWhere(['between', 'pedido_libro.created_at', strtotime(date('d-m-Y', strtotime("-1 months"))), strtotime(date('d-m-Y', time()))]);
        }
        if($params['PedidoSearch']['titulo']){
            $query->andFilterWhere([
                'or',
                ['like', 'pedido_libro.numero_pedido', $params['PedidoSearch']['titulo']],
                ['like','pedido_libro.costo_total', $params['PedidoSearch']['titulo']],
                ['like','estado_pedido.nombre', $params['PedidoSearch']['titulo']],
                ['like','clientes.nombre', $params['PedidoSearch']['titulo']]
            ]);
        }
        $resultado = $query->one();
        return $resultado->total == NULL ? 0 : $resultado->total;
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

    public function getCantidad($id,$editorial = false)
    {
        $total = 0;
        if(!$editorial){
            $query = LibroPedido::find()
            ->where('pedido_libro_id='.$id)->all();
        }else{
            $query = LibroPedido::find()
            ->where('libro.editorial_id = '.$editorial)
            ->andWhere('pedido_libro_id='.$id)
            ->join('INNER JOIN','libro','libro.id = libro_pedido.libro_id')
            ->all();
        }
        foreach ($query as $value) {
            $total = $value->cantidad+$total;
        }
        return $total;
    }

    public function getMonto($id,$editorial = false)
    {
        $total = 0;
        if(!$editorial){
            $query = LibroPedido::find()
            ->where('pedido_libro_id='.$id)->all();
        }else{
            $query = LibroPedido::find()
            ->where('libro.editorial_id = '.$editorial)
            ->andWhere('pedido_libro_id='.$id)
            ->join('INNER JOIN','libro','libro.id = libro_pedido.libro_id')
            ->all();
        }
        foreach ($query as $value) {
            $total = $value->total+$total;
        }
        return $total;
    }

    public function pedUnicos(){
        return $query = PedidoLibro::find()
        ->select(['pedido_libro.id','pedido_libro.clientes_id', 'clientes.email AS email', 'clientes.nombre AS nombre'])
        ->join('INNER JOIN', 'clientes', 'pedido_libro.clientes_id = clientes.id')
        ->orderBy(['pedido_libro.id'=>SORT_DESC])->all();
        // var_dump($query->createCommand()->getRawSql());exit;
    }
}
?>
