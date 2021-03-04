<?php

namespace frontend\models;

use Yii;


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
            [['fondo', 'portada', 'libro_id'], 'required'],
            [['libro_id'], 'integer'],
            [['fondo', 'portada'], 'string', 'max' => 100],
            [['descripcion'], 'string'],
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
            'fondo'  => 'Fondo',
            'portada' => 'Portada',
            'descripcion' => 'Descripcion',
            'libro_id' => 'ID Libro' 
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
            -> where('libro_id='.$id)->one();

        return $doc;
    }
    public function verPortada($id)
    {
        $img = $this-> find()
            //->select([
            //'documentos.imagen',
             //])
            // -> join('INNER JOIN', 'datos_fiscales', 'datos_fiscales.cliente_id = cliente.id')
            // -> join('INNER JOIN', 'direccion_envio', 'direccion_envio.cliente_id = cliente.id')
            -> where('libro_id='.$id)->one();

        return $img;
    }
    public function getImgPortada($name)
    {   
            $imgportada = Libro::find()
            ->select('libro.titulo, imagenes.portada, imagenes.libro_id')
            ->innerjoin('imagenes', 'libro.id = imagenes.libro_id')
            ->where('libro.titulo="'.$name.'"')
            ->all();

        
        return $imgportada;
    }
    
}
