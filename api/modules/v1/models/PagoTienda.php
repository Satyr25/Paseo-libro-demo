<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class PagoTienda extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pago_tienda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cookie_id', 'usuario_id', 'total'], 'required'],
            [['orden_id', 'payment_method', 'referencia', 'barcode_url', 'monto'], 'required'],
            [['orden_id', 'payment_method', 'referencia', 'barcode_url'], 'string'],
            [['monto'], 'safe'],

            //[['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pedido_libro_id' => 'Pedido Libro ID',
            'orden_id' => 'Orden ID',
            'payment_method' => 'Metodo de Pago',
            'referencia' => 'No referencia',
            'barcode_url' => 'Codigo de Barras',
            'monto' => 'Monto',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
        /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoLibro()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'pedido_libro_id']);
    }
}
