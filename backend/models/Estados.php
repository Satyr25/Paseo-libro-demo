<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estados".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $clave
 *
 * @property Devolucion[] $devolucions
 * @property Entrega[] $entregas
 * @property Ingreso[] $ingresos
 * @property Paquete[] $paquetes
 * @property Pedido[] $pedidos
 */
class Estados extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estados';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'clave'], 'required'],
            [['nombre', 'clave'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'clave' => 'Clave',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevolucions()
    {
        return $this->hasMany(Devolucion::className(), ['estados_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntregas()
    {
        return $this->hasMany(Entrega::className(), ['estados_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIngresos()
    {
        return $this->hasMany(Ingreso::className(), ['estados_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaquetes()
    {
        return $this->hasMany(Paquete::className(), ['estados_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidos()
    {
        return $this->hasMany(Pedido::className(), ['estados_id' => 'id']);
    }

    public function obtienePorClave($clave)
    {
        return $this->find()->where(['clave' => $clave])->one();
    }
}
