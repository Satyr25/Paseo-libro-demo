<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "editorial".
 *
 * @property int $id
 * @property string $clave
 * @property string $nombre
 * @property string $contacto
 * @property string $telefono
 * @property string $correo
 * @property int $activo
 *
 * @property Libro[] $libros
 */
class Editorial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'editorial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'activo', 'clave'], 'required'],
            [['activo'], 'integer'],
            [['clave', 'nombre', 'contacto', 'correo'], 'string', 'max' => 255],
            [['telefono'], 'string', 'max' => 20],
            ['email', 'email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clave' => 'Clave',
            'nombre' => 'Nombre',
            'contacto' => 'Contacto',
            'telefono' => 'Telefono',
            'correo' => 'Correo',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::className(), ['editorial_id' => 'id']);
    }
    
    public function obtenerEditorial($editorial_id){
        $editorial = Editorial::find()->where(['activo' => '1', 'id' => $editorial_id])->one();
        return $editorial;
    }
}