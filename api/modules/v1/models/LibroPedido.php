<?php

namespace api\modules\v1\models;

use Yii;


class LibroPedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'libro_pedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pedido_libro_id', 'libro_id', 'total'], 'required'],
            [['pedido_libro_id', 'libro_id', 'cantidad'], 'integer'],
            [['total'], 'number'],
            [['pedido_libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => PedidoLibro::className(), 'targetAttribute' => ['pedido_libro_id' => 'id']],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pedido_libro_id' => 'Pedido Libro ID',
            'Libro_id' => 'Libro ID',
            'cantidad' => 'Cantidad',
            'total' => 'Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoLibro()
    {
        return $this->hasOne(PedidoLibro::className(), ['id' => 'pedido_libro_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }

}
