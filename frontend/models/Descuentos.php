<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "descuentos".
 *
 * @property int $id
 * @property string $codigo
 * @property double $porcentaje
 * @property int $activo
 *
 * @property PedidoLibro[] $pedidoLibros
 */
class Descuentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'descuentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'porcentaje'], 'required'],
            [['porcentaje'], 'number'],
            [['activo', 'global'], 'integer'],
            [['codigo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'porcentaje' => 'Porcentaje',
            'activo' => 'Activo',
            'global' => 'Cupón Global'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPedidoLibros()
    {
        return $this->hasMany(PedidoLibro::className(), ['descuento_id' => 'id']);
    }
}
