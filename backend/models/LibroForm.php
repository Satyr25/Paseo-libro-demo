<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;
use backend\models\Libro;
use backend\models\Imagenes;
use backend\models\Autor;
use backend\models\LibroAutor;


class LibroForm extends Model
{
    public $codigo_barras;
    public $cantidad;
    public $titulo;
    public $pvp;
    public $autor;
    public $subtitulo;
    public $isbn;
    public $interno;
    public $anio;
    public $sello_id;
    public $coleccion_id;
    public $tema_id;
    public $paginas;
    public $peso;
    public $profundo;
    public $alto;
    public $largo;
    public $portada;
    public $descripcion;
    public $editorial_id;
    public $activo;
    public $fondo;
    public $id;

    private $transaction;
    private $imagen;

    public function rules()
    {
        return [
            [['isbn'], 'unique'],
            [['titulo', 'tema_id', 'sello_id', 'coleccion_id', 'codigo_barras','isbn'], 'required', 'message' => '{attribute} no puede estar vacío'],
            [['cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'editorial_id', 'id'], 'integer'],
            [['peso', 'profundo', 'largo', 'alto', 'pvp'], 'number'],
            [['titulo', 'subtitulo', 'codigo_barras', 'autor', 'descripcion' ], 'string', 'max' => 512],
            [['isbn', 'anio', 'interno'], 'string', 'max' => 45],
            [['portada', 'fondo'],'file', 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'codigo_barras' => 'Código de Barras',
            'cantidad' => 'Inventario Disponible',
            'titulo' => 'Titulo',
            'pvp' => 'Precio de Venta al Público',
            'autor' => 'Autor',
            'subtitulo' => 'Subtitulo',
            'isbn' => 'ISBN',
            'interno' => 'Interno',
            'anio' => 'Edición',
            'sello_id' => 'Sello',
            'coleccion_id' => 'Colección',
            'tema_id' => 'Tema',
            'paginas' => 'Páginas',
            'peso' => 'Peso',
            'profundo' => 'Profundo',
            'alto' => 'Alto',
            'largo' => 'Largo',
            'portada' => 'Portada',
            'descripcion' => 'Descripción',
            'activo' => 'Activo',
        ];
    }


    public function guardar(){
        $libro = new Libro();
        $libro->codigo_barras = $this->codigo_barras;
        $libro->cantidad = $this->cantidad;
        $libro->titulo = $this->titulo;
        $libro->pvp = $this->pvp;
        $libro->autor = $this->autor;
        $libro->subtitulo = $this->subtitulo;
        $libro->tema_id = $this->tema_id;
        $libro->sello_id = $this->sello_id;
        $libro->coleccion_id = $this->coleccion_id;
        $libro->isbn = $this->isbn;
        $libro->anio = $this->anio;
        $libro->interno = $this->interno;
        $libro->paginas = $this->paginas;
        $libro->peso = $this->peso;
        $libro->profundo = $this->profundo;
        $libro->alto = $this->alto;
        $libro->largo = $this->largo;
        $libro->editorial_id = $this->editorial_id;

        if (!$libro->save()) {
            return false;
        }

        $images = new Imagenes();

        $images->descripcion = $this->descripcion;
        $images->libro_id = $libro->id;

        $ruta_portada = $this->guardaPortada('portada');
        if($ruta_portada){
            $images->portada = $ruta_portada;
        }
       $ruta_fondo = $this->guardaFondo('fondo');
       if($ruta_fondo){
           $images->fondo = $ruta_fondo;
       }

        if (!$images->save()){
            return false;
        }
        return true;
    }


    public function guardaPortada($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/portadas');
        $ruta_frontend = Yii::getAlias('@frontend/web/images/portadas');
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
        $ruta_bd = 'portadas/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }

    public function guardaFondo($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/fondos');
        $ruta_frontend = Yii::getAlias('@frontend/web/images/fondos');
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
        $ruta_bd = 'fondos/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }

    public function actualizar($id){

        $connection = \Yii::$app->db;
        $this->transaction = $connection->beginTransaction();
        $autor = Autor::find()->where(['nombre' => $this->autor])->one();
        if ($autor == null){
            $autor = new Autor();
            $autor->nombre = $this->autor;
            if(!$autor->save()){
                $this->transaction->rollback();
                Yii::$app->session->setFlash('error', 'No se pudo guardar el autor');
                return false;
            }
            $libro_autor = LibroAutor::find()->where(['libro_id' => $id])->one();
            $libro_autor->autor_id = $autor->id;
            if(!$libro_autor->save()){
                $this->transaction->rollback();
                Yii::$app->session->setFlash('error', 'No se pudo guardar la relación libro-autor');
                return false;
            }
        } else {
            $libro_autor = LibroAutor::find()->where(['libro_id' => $id, 'autor_id' => $autor->id]);
            if ($libro_autor == null){
                $libro_autor = new LibroAutor;
                $libro_autor->libro_id = $id;
                $libro_autor->autor_id = $autor->id;
                if(!$libro_autor->save()){
                    $this->transaction->rollback();
                    Yii::$app->session->setFlash('error', 'No se pudo guardar la relación libro-autor');
                    return false;
                }
            }
        }

        $libro = Libro::findOne($id);
        $libro->codigo_barras = $this->codigo_barras;
        $libro->cantidad = $this->cantidad;
        $libro->titulo = $this->titulo;
        $libro->pvp = $this->pvp;
        $libro->subtitulo = $this->subtitulo;
        $libro->tema_id = $this->tema_id;
        $libro->sello_id = $this->sello_id;
        $libro->coleccion_id = $this->coleccion_id;
        $libro->isbn = $this->isbn;
        $libro->anio = $this->anio;
        $libro->interno = $this->interno;
        $libro->paginas = $this->paginas;
        $libro->peso = $this->peso;
        $libro->profundo = $this->profundo;
        $libro->alto = $this->alto;
        $libro->largo = $this->largo;
        $libro->editorial_id = $this->editorial_id;


        if (!$libro->save()) {
            $this->transaction->rollback();
            Yii::$app->session->setFlash('error', 'No se pudo actualizar el contenido. '.$libro->getErrors());
            return false;
        }

        $images = Imagenes::find()->where(['libro_id' => $id])->one();
        if(!$images){
            $images = new Imagenes();
            $images->libro_id = $libro->id;
        }

        $images->descripcion = $this->descripcion;

        $ruta_portada = $this->actualizaPortada('portada');
        if($ruta_portada){
            $images->portada = $ruta_portada;
        }
       $ruta_fondo = $this->actualizaFondo('fondo');
       if($ruta_fondo){
           $images->fondo = $ruta_fondo;
       }

        if (!$images->save()){
            $this->transaction->rollback();
            Yii::$app->session->setFlash('error', 'No se logró actualizar las imagenes');
            return false;
        }
        $this->transaction->commit();
        return true;
    }


    public function actualizaPortada($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/portadas');
        $ruta_frontend = Yii::getAlias('@frontend/web/images/portadas');
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
        $ruta_bd = 'portadas/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }

    public function actualizaFondo($imagen){
        if (!UploadedFile::getInstance($this, $imagen)){
            return null;
        }
        $this->imagen = UploadedFile::getInstance($this, $imagen);
        $ruta = Yii::getAlias('@backend/web/images/fondos');
        $ruta_frontend = Yii::getAlias('@frontend/web/images/fondos');
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
        $ruta_bd = 'fondos/'.preg_replace("/[^a-z0-9\.]/", "", strtolower($nombre_archivo));
        return $ruta_bd;
    }
}
