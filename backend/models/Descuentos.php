<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "descuentos".
 *
 * @property int $id
 * @property string $codigo
 * @property double $procentaje
 * @property int $activo
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
            'procentaje' => 'Porcentaje',
            'activo' => 'Activo',
            'global' => 'Cupón Global'
        ];
    }
}
