<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "inventario".
 *
 * @property integer $id
 * @property integer $libro_id
 * @property integer $fiscales_editorial_id
 * @property integer $cantidad
 *
 * @property FiscalesEditorial $fiscalesEditorial
 * @property Libro $libro
 */
class Inventario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inventario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['libro_id', 'fiscales_editorial_id'], 'required'],
            [['libro_id', 'fiscales_editorial_id', 'cantidad'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'libro_id' => 'Libro ID',
            'fiscales_editorial_id' => 'Fiscales Editorial ID',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiscalesEditorial()
    {
        return $this->hasOne(FiscalesEditorial::className(), ['id' => 'fiscales_editorial_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }

    public function disponibles($libro, $razon_social){
        $inventario = $this->find()->
            where('libro_id='.$libro.' AND fiscales_editorial_id='.$razon_social)
            ->one();
        return $inventario->cantidad;
    }

    public function disponiblesTotales($libro){
        $inventario = $this->find()
            ->select('SUM(cantidad) AS cantidad')
            ->where('libro_id = '.$libro)
            ->one();
        return $inventario->cantidad ? $inventario->cantidad : 0;
    }

    public function restar($pedido,$libros){
        $pedido = Pedido::findOne($pedido);
        foreach(array_filter($libros) as $id => $cantidad){
            $inventario = $this->find()
                ->where('libro_id='.$id.' AND fiscales_editorial_id='.$pedido->fiscales_editorial_id)
                ->one();

            $inventario->cantidad -= $cantidad;
            if($inventario->cantidad < 0)
                return false;

            if(!$inventario->update())
                return false;

        }
        return true;
    }
}
