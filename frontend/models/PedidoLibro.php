<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class PedidoLibro extends \yii\db\ActiveRecord
{
    public $libro_pedido;
    public $libro;
    public $precio;
    public $total;
    public $cantidad;
    public $fecha;
    public $cliente;
    public $estado;
    public $nombre;
    public $apellidos;
    public $calle;
    public $colonia;
    public $cp;
    public $telefono;
    public $email;
    public $estados_mundo_id;
    public $num_ext;
    public $num_int;
    public $ciudad;
    public $delegacion;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pedido_libro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado_pedido_id'], 'required'],
            [['numero_pedido'], 'string'],
            [['tracking'], 'string', 'max' => 25],
            [['costo_total', 'costo_envio'], 'number'],
            [['created_at', 'updated_at','estado_pedido_id'], 'integer'],
            [['clientes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['clientes_id' => 'id']],
            [['datos_pago_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatosPago::className(), 'targetAttribute' => ['datos_pago_id' => 'id']],
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clientes_id' => 'Cliente ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'datos_pago_id' => 'Datos Pago ID',
            'tracking' => 'Tracking',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasOne(Clientes::className(), ['id' => 'clientes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatosPago()
    {
        return $this->hasOne(DatosPago::className(), ['id' => 'datos_pago_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibroPedidos()
    {
        return $this->hasMany(LibroPedido::className(), ['pedido_libro_id' => 'id']);
    }

    public function productos($pedido){
        $productos = $this->find()
        ->select([
            'libro_pedido.id AS libro_pedido',
            'libro.id AS libro',
            'libro.titulo AS titulo',
            'libro.pvp AS precio',
            'libro_pedido.cantidad AS cantidad',
            'libro_pedido.total AS total',
        ])
        ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.pedido_libro_id = pedido_libro.id')
        ->join('INNER JOIN', 'libro', 'libro.id = libro_pedido.libro_id')
        ->where('pedido_libro.id ='.$pedido)
        ->all();
        return $productos;
    }

    public function datosPedido($id){
        return $this->find()
            ->select([
                'pedido_libro.id AS id', 
                'pedido_libro.numero_pedido AS numero_pedido',
                'pedido_libro.created_at', 
                'estado_pedido.nombre AS estado'
            ])
            ->join('INNER JOIN', 'estado_pedido', 'estado_pedido.id = pedido_libro.estado_pedido_id')
            ->where('pedido_libro.id = '.$id)
            ->one();
    }

    public function obtenerNumero(){
        do{
            $numero = $this->randomString(8);
            $existente = $this->find()->where('numero_pedido="'.$numero.'"')->one();
            if(!$existente)
                return $numero;
        }while(true);
    }

    private function randomString($length){
        $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $string = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
             $string .= $chars[rand(0, $max)];
        }
        return $string;
    }

    public function verPedidos($id)
    {
        $pedidos = $this-> find()
            -> where('pedido_libro.id='.$id)->one();

        return $pedidos;
    }
}
