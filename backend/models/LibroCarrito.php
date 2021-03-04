<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "libro_carrito".
 *
 * @property int $id
 * @property int $carrito_id
 * @property int $cantidad
 * @property int $libro_id
 *
 * @property Carrito $carrito
 * @property Libro $libro
 */
class LibroCarrito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $titulo;
    public $promo;
    public $precio;
    
    
    public static function tableName()
    {
        return 'libro_carrito';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['carrito_id', 'libro_id'], 'required'],
            [['carrito_id', 'cantidad', 'libro_id'], 'integer'],
            [['carrito_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrito::className(), 'targetAttribute' => ['carrito_id' => 'id']],
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
            'carrito_id' => 'Carrito ID',
            'cantidad' => 'Cantidad',
            'libro_id' => 'Libro ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarrito()
    {
        return $this->hasOne(Carrito::className(), ['id' => 'carrito_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }
}
