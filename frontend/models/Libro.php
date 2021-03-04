<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\LibroAutor;
use frontend\models\Autor;
use frontend\models\Inventario;
use frontend\models\Estados;
use frontend\models\Ingreso;
use frontend\models\Imagenes;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use frontend\models\Sello;
use yii\db\Expression;
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
    public $portada;
    public $fondo;
    public $orden;
    public $finales;
    public $marca;
    public $numero;
    public $novedad;
    public $mas_vendido;
    public $mostrar;
    public $descripcion;
    public $editorial;


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
            [['titulo', 'tema_id', 'sello_id', 'coleccion_id', 'codigo_barras',
              'isbn',
              ], 'required'],
            [['interno', 'cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'iva', 'novedad', 'mas_vendido', 'mostrar'], 'integer'],
            [['peso', 'profundo', 'largo', 'alto', 'pvp', 'costo', 'promo'], 'number'],
            [['titulo', 'subtitulo', 'codigo_barras'], 'string', 'max' => 512],
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
            'mostrar' => 'Mostrar',
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
    public function getImagenes()
    {
        return $this->hasOne(Imagenes::className(), ['libro_id' => 'id']);
    }
    

    public function verLibros($id){
        $libros = $this->find()
             ->select([
                 'libro.*',
                 'editorial.nombre as editorial',
                 'autor.nombre as autor',
                 'tema.nombre as tema',
                 'tema.id as tema_id',
             ])
             ->join('INNER JOIN', 'editorial', 'libro.editorial_id = editorial.id')
             ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
             ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
             ->join('INNER JOIN', 'tema', 'tema.id = libro.tema_id')
             ->where(['libro.id' => $id, 'mostrar' => 1])
             ->andWhere('cantidad > 0')
             ->one();
        return $libros;
    }
    public function verSello($id)
    {
        $libros = $this-> find()
            // ->select([
            // 'flotilla.*',
            // 'status.clave AS status_clave',
            // ])
            // -> join('INNER JOIN', 'status', 'status.id = flotilla.status_id')
            // -> join('INNER JOIN', 'direccion_envio', 'direccion_envio.cliente_id = cliente.id')
            -> where('libro.coleccion_id='.$id)->all();

        return $libros;
    }
    public function fotoCorreo($id){
        return $this->find()
        ->select('libro.id,
            imagenes.portada AS portada')
        ->join('INNER JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->where('libro.id='.$id)
        ->one();
    }

    public function descripcionDeLibro($id){
        return $this->find()
        ->select('libro.id,
            imagenes.descripcion AS descripcion')
        ->join('INNER JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->where('libro.id='.$id)
        ->one();
    }

    public function sello_get($id){
        return $this->find()
        ->select('libro.id,
            sello.nombre AS sello')
        ->join('INNER JOIN', 'sello', 'sello.id = libro.sello_id')
        ->where('libro.id='.$id)
        ->one();
    }

    public function temas_get($id){
        return $this->find()
        ->select('libro.id,
            tema.nombre AS tema')
        ->join('INNER JOIN', 'tema', 'tema.id = libro.tema_id')
        ->where('libro.id='.$id)
        ->one();
    }

    public function autor($id){
        return $this->find()
        ->select('autor.nombre AS autor')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro.id='.$id)
        ->one();
    }

    public function obtenerNovedades(){
        return $this->find()
        ->select('libro.*, imagenes.portada, imagenes.fondo')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->where(['libro.novedad'=>1, 'libro.mostrar' => 1])
        ->andWhere('cantidad > 0')
        ->orderBy(new Expression('rand()'))
        ->limit(5)
        ->all();
    }
    public function obtenerAllNovedades(){
        $novedades =  $this->find()
            ->select('libro.*, imagenes.portada, imagenes.fondo')
            ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
            ->where(['libro.novedad'=>1, 'libro.mostrar' => 1])
            ->andWhere('libro.pvp > 3')
            ->andWhere('cantidad > 0')
            ->andWhere('imagenes.portada IS NOT null')
            ->orderBy(new Expression('rand()'))
            ->all();
        return $novedades;
    }

    public function obtenerMasVendidos(){
        $vendidos = $this->find()
        ->select('libro.*, imagenes.portada, autor.nombre as autor, tema.nombre as tema, tema.id as tema_id')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('LEFT JOIN', 'libro_autor', 'libro.id = libro_autor.libro_id')
        ->join('LEFT JOIN', 'autor', 'libro_autor.libro_id = autor.id')
        ->join('LEFT JOIN', 'tema', 'libro.tema_id = tema.id')
        ->where(['libro.mostrar' => 1])
        ->where('libro.pvp > 3')
        ->andWhere('cantidad > 0')
        ->andWhere('imagenes.portada IS NOT null')
        ->orderBy(['libro.mas_vendido' => SORT_DESC])
        ->limit(12)
        ->all();
        return $vendidos;
    }

    public function obtenerLibrosRandom($id, $tema){

        $query = $this->find()
        ->select('libro.*, imagenes.portada, autor.nombre as autor')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('INNER JOIN', 'libro_autor', 'libro.id = libro_autor.libro_id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro.mostrar = 1')
        ->where('cantidad > 0')
        ->where('libro.pvp > 3')
        ->andWhere("libro.tema_id = $tema")
        ->andWhere('libro.id NOT IN ('.$id.')')
        ->andWhere('imagenes.portada IS NOT null')
        ->orderBy(new Expression('rand()'))
        ->limit(6)
        ->all();

        return $query;
    }
}
