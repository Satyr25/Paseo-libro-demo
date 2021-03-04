<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\Editorial;
use app\models\Usuario;
use yii\web\UploadedFile;
use yii\helpers\Url;

class EditorialForm extends Model
{
    public $img_file;
    public $clave;
    public $nombre_editorial;
    public $contacto;
    public $telefono;
    public $correo;
    public $activo;
    public $usuario;
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $password;
    public $confirma_password;

    private $imagen;
    private $transaction;

    public function rules()
    {
        return [
            [['password', 'confirma_password'], 'required', 'on' => 'crear', 'message' => 'Por favor ingresa {attribute}'],
            [['clave', 'nombre_editorial', 'contacto', 'usuario', 'nombre'], 'required', 'message' => 'Por favor ingresa {attribute}'],
            [['clave', 'nombre_editorial', 'contacto', 'usuario', 'nombre', 'apellido_paterno', 'apellido_materno'], 'string'],
            [['telefono'], 'integer'],
            [['activo'], 'boolean'],
            [['correo'], 'email'],
            [['usuario'], 'unique', 'targetClass' => '\app\models\Usuario', 'targetAttribute' => 'usuario', 'message' => 'This email address has already been taken.'],
            [['img_file'],'file', 'extensions' => 'png, jpg'],
            ['password', 'string', 'min' => 6],
            ['confirma_password', 'compare', 'compareAttribute'=>'password', 'message'=>"La contraseña no coincide" ],
        ];
    }
    public function attributeLabels()
    {
        return [
            'clave' => 'Clave de Editorial',
            'nombre_editorial' => 'Nombre de Editorial',
            'contacto' => 'Contacto',
            'telefono' => 'Teléfono',
            'correo' => 'Correo',
            'activo' => 'Activo',
            'usuario' => 'Usuario',
            'nombre' => 'Nombre de Usuario',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'password' => 'Contraseña',
            'confirma_password' => 'Repite tu Contraseña',
            'img_file' => 'Subir logo',
        ];
    }


    public function guardar()
    {
        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        $editorial = new Editorial();

        $editorial->clave = $this->clave;
        $editorial->nombre = $this->nombre_editorial;
        $editorial->contacto = $this->contacto;
        $editorial->telefono = $this->telefono;
        $editorial->correo = $this->correo;
        $editorial->activo = intval($this->activo);
        $ruta = $this->guardaImagen('img_file');
        if($ruta){
            $editorial->logo = $ruta;
        }
        if (!$editorial->save()) {
            $this->transaction->rollback();
            return false;
        }

        $usuario = new Usuario();
        $usuario->usuario = $this->usuario;
        $usuario->nombre = $this->nombre;
        $usuario->ap_paterno = $this->apellido_paterno;
        $usuario->ap_materno = $this->apellido_materno;
        $usuario->correo = $this->correo;
        $usuario->editorial_id = $editorial->id;
        $usuario->rol_id = 2;
        $usuario->setPassword($this->password);
        $usuario->generateAuthKey();

        if (!$usuario->save()) {
            $this->transaction->rollback();
            return false;
        }
        $this->transaction->commit();
        return true;
    }

    public function actualizar($id)
    {
        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();

        $editorial = Editorial::find()->where(['id' => $id])->one();
        $editorial->clave = $this->clave;
        $editorial->nombre = $this->nombre_editorial;
        $editorial->contacto = $this->contacto;
        $editorial->telefono = $this->telefono;
        $editorial->correo = $this->correo;
        $editorial->activo = intval($this->activo);

        $ruta = $this->guardaImagen('img_file');
        if($ruta){
            $editorial->logo = $ruta;
        }
        if (!$editorial->save()) {
            $this->transaction->rollback();
            var_dump($editorial->getErrors());exit;
            return false;
        }

        $usuario = Usuario::find()->where(['editorial_id' => $id])->one();
        $usuario->usuario = $this->usuario;
        $usuario->nombre = $this->nombre;
        $usuario->ap_paterno = $this->apellido_paterno;
        $usuario->ap_materno = $this->apellido_materno;
        $usuario->correo = $this->correo;

        if ($this->password !== ''){
            $usuario->setPassword($this->password);
        }
        
        if (!$usuario->save()) {
            $this->transaction->rollback();
            return false;
        }
        $this->transaction->commit();
        return true;
    }
    
    public function actualizarUsuario($id)
    {

        $usuario = Usuario::find()->where(['id' => $id])->one();
        $usuario->usuario = $this->usuario;
        $usuario->nombre = $this->nombre;
        $usuario->ap_paterno = $this->apellido_paterno;
        $usuario->ap_materno = $this->apellido_materno;
        $usuario->correo = $this->correo;

        if ($this->password !== ''){
            $usuario->setPassword($this->password);
        }
        
        if (!$usuario->save()) {
            return false;
        }
        return true;
    }

    public function guardaImagen($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/').'editoriales/'.preg_replace("/[^a-z0-9\.]/", "", "");
        $ruta_frontend = Yii::getAlias('@frontend/web/images/').'editoriales/'.preg_replace("/[^a-z0-9\.]/", "", "");
        if(!file_exists($ruta)){
            if(!mkdir($ruta)){
                return false;
            }
        }
        if(!file_exists($ruta_frontend)){
            if(!mkdir($ruta_frontend)){
                return false;
            }
        }
        $guardado = false;
        while(!$guardado){
            $timestamp = time();
            $nombre_archivo = $timestamp.preg_replace("/[^a-z0-9\.]/", "", strtolower($this->imagen));
            if(!file_exists($ruta.'/'.$nombre_archivo)){
                if(!$this->imagen->saveAs($ruta.'/'.$nombre_archivo, false )){
                    return false;
                }
                if(!$this->imagen->saveAs($ruta_frontend.'/'.$nombre_archivo, false )){
                    return false;
                }
             $guardado = true;
             }
        }
        $ruta_bd = 'editoriales/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }
}
