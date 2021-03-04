<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "ingreso".
 *
 * @property integer $id
 * @property string $fecha
 * @property integer $cantidad
 * @property integer $libro_id
 * @property integer $estados_id
 * @property string $comentarios
 * @property integer $fiscales_editorial_id
 *
 * @property Estados $estados
 * @property FiscalesEditorial $fiscalesEditorial
 * @property Libro $libro
 */
class Ingreso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ingreso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha', 'cantidad', 'libro_id', 'estados_id', 'fiscales_editorial_id'], 'required'],
            [['fecha'], 'safe'],
            [['cantidad', 'libro_id', 'estados_id', 'fiscales_editorial_id'], 'integer'],
            [['comentarios'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'cantidad' => 'Cantidad',
            'libro_id' => 'Libro ID',
            'estados_id' => 'Estados ID',
            'comentarios' => 'Comentarios',
            'fiscales_editorial_id' => 'Fiscales Editorial ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstados()
    {
        return $this->hasOne(Estados::className(), ['id' => 'estados_id']);
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
}
