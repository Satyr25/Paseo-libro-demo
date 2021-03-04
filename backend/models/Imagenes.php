<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "datos_fiscales".
 *
 * @property int $id
 * @property int $cliente_id
 * @property string $rfc
 * @property string $calle
 * @property string $numero_int
 * @property string $numero_ext
 * @property string $colonia
 * @property string $municipio
 * @property string $estado
 * @property int $cp
 *
 * @property Cliente $cliente
 */
class Imagenes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'imagenes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['libro_id'], 'required'],
            [['libro_id'], 'integer'],
            [['fondo', 'portada'], 'string', 'max' => 255],
            [['descripcion'], 'string'],
            [['libro_id'], 'exist', 'skipOnError' => false, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fondo'  => 'Fondo',
            'portada' => 'Portada',
            'descripcion' => 'Descripcion',
            'libro_id' => 'ID Libro', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }
    public function verDocumento($id)
    {
        $doc = $this-> find()
            //->select([
            //'documentos.imagen',
             //])
            // -> join('INNER JOIN', 'datos_fiscales', 'datos_fiscales.cliente_id = cliente.id')
            // -> join('INNER JOIN', 'direccion_envio', 'direccion_envio.cliente_id = cliente.id')
            -> where('libro_id='.$id)->all();

        return $doc;
    }
}
