<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use app\models\LibroAutor;
use app\models\Editorial;
use app\models\Autor;
use app\models\Tema;
use app\models\Inventario;
use app\models\Estados;
use app\models\Ingreso;
use backend\models\Imagenes;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "libro".
 *
 * @property integer $id
 * @property string $titulo
 * @property string $subtitulo
 * @property string $codigo_barras
 * @property integer $interno
 * @property integer $cantidad
 * @property string $isbn
 * @property string $anio
 * @property integer $paginas
 * @property string $peso
 * @property string $profundo
 * @property string $largo
 * @property string $alto
 * @property string $pvp
 * @property integer $tema_id
 * @property integer $sello_id
 * @property integer $coleccion_id
 *
 * @property Coleccion $coleccion
 * @property Sello $sello
 * @property Tema $tema
 * @property LibroAutor[] $libroAutors
 * @property LibrosDevolucion[] $librosDevolucions
 * @property LibrosPedido[] $librosPedidos
 */
class Libro extends \yii\db\ActiveRecord
{
    public $sello;
    public $autor;
    public $tema;
    public $coleccion;
    public $fiscales_editorial_id;
    public $autor_id;
    public $descripcion;
    public $nombre_sello;
    public $portada;
    public $editorial;
    public $fondo;
//    public $tema_id;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'libro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo_barras'], 'unique', 'message' => 'Ya existe un registro con este {attribute}. '],
            [['titulo', 'tema_id', 'sello_id', 'coleccion_id', 'codigo_barras','isbn'], 'required', 'message' => '{attribute} no puede estar vacío. '],
            [['cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'iva', 'novedad', 'mas_vendido', 'editorial_id', 'recomendacion'], 'integer'],
            [['peso', 'profundo', 'largo', 'alto', 'pvp', 'costo', 'promo'], 'number', 'message' => '{attribute} debe ser un número'],
            [['titulo', 'subtitulo', 'codigo_barras', 'interno'], 'string', 'max' => 512],
            [['ubicacion'], 'string', 'max' => 128],
            [['isbn', 'anio', 'clave_sat'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Título',
            'subtitulo' => 'Subtitulo',
            'codigo_barras' => 'Código de barras',
            'interno' => 'Interno',
            'cantidad' => 'Cantidad',
            'isbn' => 'Isbn',
            'anio' => 'Anio',
            'paginas' => 'Paginas',
            'peso' => 'Peso',
            'profundo' => 'Profundo',
            'largo' => 'Largo',
            'alto' => 'Alto',
            'pvp' => 'Pvp',
            'tema_id' => 'Tema ID',
            'sello_id' => 'Sello ID',
            'coleccion_id' => 'Coleccion ID',
            'iva' => 'IVA',
            'ubicacion' => 'Ubicación',
            'descripcion' => 'Descripción',
            'novedad' => 'Novedad',
            'mas_vendido' => 'Mas Vendido',
            'promo' => 'Precio de Promoción'

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColeccion()
    {
        return $this->hasOne(Coleccion::className(), ['id' => 'coleccion_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSello()
    {
        return $this->hasOne(Sello::className(), ['id' => 'sello_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTema()
    {
        return $this->hasOne(Tema::className(), ['id' => 'tema_id']);
    }

    public function verLibros($id){
        $libros = $this->find()
             ->select([
                 'libro.*',
                 'editorial.nombre as editorial',
                 'autor.nombre as autor',
                 'tema.nombre as tema',
                 'tema.id as tema_id',
                 'sello.nombre as sello',
                 'coleccion.nombre as coleccion',
                 'imagenes.fondo as fondo',
                 'imagenes.portada as portada',
                 'imagenes.descripcion as descripcion',
             ])
             ->join('INNER JOIN', 'editorial', 'libro.editorial_id = editorial.id')
             ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
             ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
             ->join('INNER JOIN', 'tema', 'tema.id = libro.tema_id')
             ->join('INNER JOIN', 'sello', 'libro.sello_id = sello.id')
             ->join('INNER JOIN', 'coleccion' , 'libro.coleccion_id = coleccion.id')
             ->join('LEFT jOIN', 'imagenes', 'imagenes.libro_id = libro.id')
             ->where(['libro.id' => $id, 'mostrar' => 1])
             ->one();
        if (Yii::$app->user->identity->editorial_id !== NULL){
            if ($libros->editorial_id !== Yii::$app->user->identity->editorial_id){
                return false;
            }
        }
        return $libros;
    }

    public function actualizarLibro($datos){
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            FileHelper::createDirectory(Yii::getAlias('@backend')."/web/fondos", 0777);
            FileHelper::createDirectory(Yii::getAlias('@backend')."/web/portadas", 0777);
            FileHelper::createDirectory(Yii::getAlias('@frontend')."/web/images/fondos", 0777);
            FileHelper::createDirectory(Yii::getAlias('@frontend')."/web/images/portadas", 0777);
            $model->imageFilef = UploadedFile::getInstance($model, 'imageFilef');
            $model->imageFilep = UploadedFile::getInstance($model, 'imageFilep');

            if ($model->upload($datos)) {
                return;
            }
        }

        return true;
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // el archivo se subió exitosamente
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function actualizarPrecio($datos){
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {

            if ($model->precio($datos)) {
                return;
            }
        }

        return true;
    }

    public function traerLibros()
    {
        $libros = $this-> find()
            ->where('mostrar = 1')->all();

        return $libros;
    }
}
