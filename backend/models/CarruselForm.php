<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\Carrusel;

class CarruselForm extends Model
{
    public $id;
    public $titulo;
    public $imagen;
    public $status;
    public $url;

    private $carrusel;

    public function rules()
    {
        return [
            [['titulo'], 'required'],
            [['titulo', 'url'], 'string'],
            [['status', 'id'], 'integer'],
            [['imagen'], 'file', 'extensions' => 'jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'titulo' => 'Título',
            'imagen' => 'Imagen',
            'status' => 'Visible'
        ];
    }

    public function guardar($actualizar = false){
        if($actualizar){
            $this->carrusel = Carrusel::findOne($this->id);
        }else{
            $this->carrusel = new Carrusel();
        }
        $this->carrusel->titulo = $this->titulo;
        if(!$this->guardaImagen($actualizar)){
            return false;
        }
        $this->carrusel->status = $this->status;
        if(strpos($this->url, "http://") === false || strpos($this->url, "https://") === false){
            $this->carrusel->url = 'http://'.$this->url;
        }else{
            $this->carrusel->url = $this->url;
        }
        if(!$this->carrusel->save()){
            return false;
        }
        return true;
    }

    public function guardaImagen($actualizar){
        if (!UploadedFile::getInstance($this, 'imagen') && $actualizar){
            return true;
        }
        $this->imagen = UploadedFile::getInstance($this, 'imagen');
        $ruta = Yii::getAlias('@backend/web/images/').'carrusel/'.preg_replace("/[^a-z0-9\.]/", "", "");
        $ruta_frontend = Yii::getAlias('@frontend/web/images/').'carrusel/'.preg_replace("/[^a-z0-9\.]/", "", "");
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
            if(!file_exists($ruta.$nombre_archivo)){
                if(!$this->imagen->saveAs($ruta.$nombre_archivo, false )){
                    return false;
                }
                if(!$this->imagen->saveAs($ruta_frontend.'/'.$nombre_archivo, false )){
                    return false;
                }
                $guardado = true;
             }
        }
        $this->carrusel->imagen = 'carrusel/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return true;
    }
}
