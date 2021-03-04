<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pedido_libro".
 *
 * @property int $id
 * @property string $numero_pedido
 * @property string $costo_total
 * @property int $created_at
 * @property int $updated_at
 * @property int $estado_pedido_id
 * @property int $clientes_id
 * @property int $datos_pago_id
 * @property int $pago_tienda_id
 * @property string $nombre_envio
 *
 * @property LibroPedido[] $libroPedidos
 * @property PagoTienda[] $pagoTiendas
 * @property EstadoPedido $estadoPedido
 * @property Clientes $clientes
 * @property DatosPago $datosPago
 * @property PagoTienda $pagoTienda
 */
class PedidoLibro extends \yii\db\ActiveRecord
{
    public $cantidad;
    public $nombre;
    public $apellidos;
    public $estado;
    public $email;
    public $total_libro;
    public $editorial_id;
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
            [['costo_total'], 'number'],
            [['created_at', 'updated_at', 'estado_pedido_id', 'clientes_id'], 'required'],
            [['created_at', 'updated_at', 'estado_pedido_id', 'clientes_id', 'datos_pago_id', 'pago_tienda_id'], 'integer'],
            [['numero_pedido'], 'string', 'max' => 10],
            [['nombre_envio'], 'string', 'max' => 45],
            [['tracking'], 'string', 'max' => 255],
            [['estado_pedido_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoPedido::className(), 'targetAttribute' => ['estado_pedido_id' => 'id']],
            [['clientes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['clientes_id' => 'id']],
            [['datos_pago_id'], 'exist', 'skipOnError' => true, 'targetClass' => DatosPago::className(), 'targetAttribute' => ['datos_pago_id' => 'id']],
            [['pago_tienda_id'], 'exist', 'skipOnError' => true, 'targetClass' => PagoTienda::className(), 'targetAttribute' => ['pago_tienda_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_pedido' => 'Numero Pedido',
            'costo_total' => 'Costo Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'estado_pedido_id' => 'Estado Pedido ID',
            'clientes_id' => 'Clientes ID',
            'datos_pago_id' => 'Datos Pago ID',
            'pago_tienda_id' => 'Pago Tienda ID',
            'nombre_envio' => 'Nombre Envio',
            'tracking' => 'tracking',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibroPedidos()
    {
        return $this->hasMany(LibroPedido::className(), ['pedido_libro_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagoTiendas()
    {
        return $this->hasMany(PagoTienda::className(), ['pedido_libro_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoPedido()
    {
        return $this->hasOne(EstadoPedido::className(), ['id' => 'estado_pedido_id']);
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
    public function getPagoTienda()
    {
        return $this->hasOne(PagoTienda::className(), ['id' => 'pago_tienda_id']);
    }
    public function verPedidos($id)
    {
        
//            var_dump(Yii::$app->user->identity->editorial_id);exit;
        $pedidos = $this-> find()
            ->where('pedido_libro.id='.$id)
            ->one();

        return $pedidos;
    }
}