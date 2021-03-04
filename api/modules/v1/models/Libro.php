<?php

namespace api\modules\v1\models;

use Yii;
use yii\base\Model;
use frontend\models\LibroAutor;
use frontend\models\Inventario;
use frontend\models\Estados;
use frontend\models\Ingreso;
use frontend\models\Imagenes;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use frontend\models\Sello;
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
    public $orden;
    public $finales;
    public $marca;
    public $numero;


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
            [['titulo', 'interno', 'tema_id', 'sello_id', 'coleccion_id', 'codigo_barras',
              'isbn', 'anio',
              ], 'required'],
            [['interno', 'cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'iva'], 'integer'],
            [['peso', 'profundo', 'largo', 'alto', 'pvp', 'costo'], 'number'],
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

    public function verLibros($id)
    {
        $libros = $this-> find()
            // ->select([
            // 'flotilla.*',
            // 'status.clave AS status_clave',
            // ])
            // -> join('INNER JOIN', 'status', 'status.id = flotilla.status_id')
            // -> join('INNER JOIN', 'direccion_envio', 'direccion_envio.cliente_id = cliente.id')
            -> where('libro.id='.$id)->one();

        return $libros;
    }

    public function autor($id){
        return $this->find()
        ->select('autor.nombre AS autor')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro.id='.$id)
        ->one();
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
    // public function datosTarjeta($id){
    //     return $this->find()
    //     ->select('libro.titulo,
    //         datos_pago.orden_id AS orden, 
    //         datos_pago.numeros_tarjeta AS finales,
    //         datos_pago.marca AS marca')
    //     ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.libro_id = libro.id')
    //     ->join('INNER JOIN', 'pedido_libro', 'pedido_libro.id = libro_pedido.pedido_libro_id')
    //     ->join('INNER JOIN', 'datos_pago', 'datos_pago.id = pedido_libro.datos_pago_id')
    //     ->where('libro.id='.$id)
    //     ->one();
    // }
    // public function obtenerOrden($id){
    //     //var_dump($id);exit;
    //     return $this->find()
    //     ->select('libro.titulo,
    //         pedido_libro.numero_pedido AS numero')
    //     ->join('INNER JOIN', 'libro_pedido', 'libro_pedido.libro_id = libro.id')
    //     ->join('INNER JOIN', 'pedido_libro', 'pedido_libro.id = libro_pedido.pedido_libro_id')
    //     ->where('libro.id='.$id)
    //     ->one();
    // }
   
}
