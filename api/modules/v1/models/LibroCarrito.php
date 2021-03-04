<?php

namespace api\modules\v1\models;

use Yii;


class LibroCarrito extends \yii\db\ActiveRecord
{

    public $titulo;
    public $libro;
    public $libro_carrito;
    public $precio;
    public $autor;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'libro_carrito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['carrito_id', 'cantidad', 'libro_id'], 'required'],
            [['carrito_id', 'cantidad'], 'integer'],
            [['carrito_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carrito::className(), 'targetAttribute' => ['carrito_id' => 'id']],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
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
