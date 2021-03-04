<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "datos_pago".
 *
 * @property int $id
 * @property string $orden_id
 * @property int $monto
 * @property int $codigo_auth
 * @property int $numeros_tarjeta
 * @property string $marca
 * @property string $tipo
 *
 * @property Pedido[] $pedidos
 */
class DatosPago extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datos_pago';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orden_id', 'monto', 'codigo_auth', 'numeros_tarjeta', 'marca', 'tipo'], 'required'],
            [['codigo_auth', 'numeros_tarjeta'], 'integer'],
            [['monto'], 'safe'],
            [['orden_id', 'marca', 'tipo'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidos()
    {
        return $this->hasMany(PedidoLibro::className(), ['datos_pago_id' => 'id']);
    }
}
