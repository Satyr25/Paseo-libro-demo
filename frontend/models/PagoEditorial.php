<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "pago_editorial".
 *
 * @property int $id
 * @property string $orden_id
 * @property double $monto
 * @property int $codigo_auth
 * @property int $numeros_tarjeta
 * @property string $marca
 * @property string $tipo
 * @property int $pedido_libro_id
 * @property int $editorial_id
 *
 * @property Editorial $editorial
 * @property PedidoLibro $pedidoLibro
 */
class PagoEditorial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pago_editorial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id', 'monto', 'codigo_auth', 'numeros_tarjeta', 'marca', 'tipo', 'pedido_libro_id', 'editorial_id'], 'required'],
            [['monto'], 'number'],
            [['codigo_auth', 'numeros_tarjeta', 'pedido_libro_id', 'editorial_id'], 'integer'],
            [['orden_id', 'marca', 'tipo'], 'string', 'max' => 255],
            [['editorial_id'], 'exist', 'skipOnError' => true, 'targetClass' => Editorial::className(), 'targetAttribute' => ['editorial_id' => 'id']],
            [['pedido_libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => PedidoLibro::className(), 'targetAttribute' => ['pedido_libro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'orden_id' => 'Orden ID',
            'monto' => 'Monto',
            'codigo_auth' => 'Codigo Auth',
            'numeros_tarjeta' => 'Numeros Tarjeta',
            'marca' => 'Marca',
            'tipo' => 'Tipo',
            'pedido_libro_id' => 'Pedido Libro ID',
            'editorial_id' => 'Editorial ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditorial()
    {
        return $this->hasOne(Editorial::className(), ['id' => 'editorial_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoLibro()
    {
        return $this->hasOne(PedidoLibro::className(), ['id' => 'pedido_libro_id']);
    }
}
