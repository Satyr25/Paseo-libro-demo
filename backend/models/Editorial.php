<?php

namespace backend\models;

use yii\web\UploadedFile;

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
 * @property string $logo
 * @property int $activo
 *
 * @property Coleccion[] $coleccions
 * @property Libro[] $libros
 * @property Sello[] $sellos
 * @property Usuario[] $usuarios
 */
class Editorial extends \yii\db\ActiveRecord
{    
    public $img_file;
    
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
            [['nombre', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['clave', 'nombre', 'contacto', 'correo', 'logo'], 'string', 'max' => 255],
            [['telefono'], 'string', 'max' => 20],
            [['img_file'],'file', 'extensions' => 'png, jpg, jpeg'],
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
            'logo' => 'Logo',
            'activo' => 'Activo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColeccions()
    {
        return $this->hasMany(Coleccion::className(), ['editorial_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::className(), ['editorial_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSellos()
    {
        return $this->hasMany(Sello::className(), ['editorial_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::className(), ['editorial_id' => 'id']);
    }
    
    public function verEditorial($id){
        $editorial = $this->find()
             ->where(['editorial.id' => $id])
             ->one();
        return $editorial;
    }
    
}