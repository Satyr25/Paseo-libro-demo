<?php 

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\Eventos;
use yii\web\UploadedFile;
use yii\helpers\Url;

class EventosForm extends Model
{
    public $img_file;
    public $tema;
    public $nombre;
    public $presentador;
    public $fecha;
    public $hora;
    public $categoria_id;
    
    private $imagen;

    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['fecha', 'categoria_id'], 'integer'],
            [['descripcion'], 'string'],
            [['nombre', 'tema', 'presentador', 'imagen'], 'string', 'max' => 255],
            [['hora'], 'string', 'max' => 45],
            [['img_file'],'file', 'extensions' => 'png, jpg'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre de evento',
            'tema' => 'Tema',
            'presentador' => 'Presentador',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'imagen' => 'Imagen',
            'descripcion' => 'Descripcion',
            'categoria_id' => 'Categoria ID',
            'img_file' => 'Subir foto',
        ];
    }

    
    public function guardar()
    {
        $evento = new Eventos();
        
        $evento->tema = $this->tema;
        $evento->nombre = $this->nombre;
        $evento->presentador = $this->presentador;
        $evento->fecha = strtotime($this->fecha);
        $evento->hora = $this->hora;
        $evento->categoria_id = $this->categoria_id;
        
        $ruta = $this->guardaImagenes('img_file');
        if($ruta){
            $evento->imagen = $ruta;
        }
//        var_dump($evento);exit;
        
        if (!$evento->save()) {
            var_dump($evento->getErrors());exit;
            return false;
        }
        return true;
    }
    
    public function update($id)
    {
        $evento = Eventos::find()->where(['id' => $id])->one();
        
        $evento->tema = $this->tema;
        $evento->nombre = $this->nombre;
        $evento->presentador = $this->presentador;
        $evento->fecha = $this->fecha;
        $evento->hora = $this->hora;
        $evento->categoria_id = $this->categoria_id;
        
        $ruta = $this->guardaImagenes('img_file');
        if($ruta){
            $evento->imagen = $ruta;
        }
//        var_dump($evento);exit;
        
        if (!$evento->save()) {
            return false;
        }
        return true;
    }
    
    public function guardaImagenes($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/').'eventos/'.preg_replace("/[^a-z0-9\.]/", "", "");
        $ruta_frontend = Yii::getAlias('@frontend/web/images/').'eventos/'.preg_replace("/[^a-z0-9\.]/", "", "");
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
        $ruta_bd = 'eventos/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }
}
