<?php
namespace backend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Libro;
use backend\models\LibroAutor;
use backend\models\Autor;
use yii\db\Expression;

class LibrosSearch extends Libro {

    public $activados;
    public $desactivados;


    public function rules()
    {
        return [
            [['interno', 'cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'iva'], 'integer'],
            [['titulo',], 'string'],
            [['peso', 'profundo', 'largo', 'alto', 'pvp', 'costo'], 'number'],
            [['titulo', 'subtitulo', 'codigo_barras', 'ubicacion', 'isbn', 'anio', 'clave_sat'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'titulo' => 'Titulo',
        ];
    }

    public function search($params)
    {
        $query = Libro::find()
            ->select('libro.*, sello.nombre as nombre_sello')
            ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
            ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
            ->join('INNER JOIN', 'sello', 'sello.id = libro.sello_id')
            ->join('INNER JOIN', 'editorial', 'editorial.id = libro.editorial_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        if(Yii::$app->user->identity->editorial_id !== NULL){
            $query->andWhere(['=', 'libro.editorial_id', Yii::$app->user->identity->editorial_id]);
        }

        if($params['LibrosSearch']['titulo']){
            $query->andFilterWhere(['like', 'libro.titulo', $params['LibrosSearch']['titulo']])
            ->orFilterWhere(['like','libro.codigo_barras', $params['LibrosSearch']['titulo']])
            ->orFilterWhere(['like','autor.nombre', $params['LibrosSearch']['titulo']])
            ->orFilterWhere(['like','sello.nombre', $params['LibrosSearch']['titulo']])
            ->orFilterWhere(['like','editorial.nombre', $params['LibrosSearch']['titulo']]);
        }

//        if($params['LibrosSearch']['activados']){
//            $query->andFilterWhere(['=', 'libro.mostrar', 1]);
//        }elseif ($params['LibrosSearch']['desactivados']) {
//            $query->andFilterWhere(['=', 'libro.mostrar', 0]);
//        }elseif ($params['LibrosSearch']['promociones'] && 'libro.promo' != null){
//            $query->andFilterWhere(['>', 'libro.promo', 0])
//            ->andFilterWhere(['=', 'libro.mostrar', 1]);
//        }else{
//            $query->andFilterWhere(['=', 'libro.mostrar', 1]);
//        }

        return $dataProvider;
    }

    public function search2($params, $codigo){
        $query = Libro::find()
            ->select('libro.*, sello.nombre as nombre_sello')
            ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
            ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
            ->join('INNER JOIN', 'sello', 'sello.id = libro.sello_id')
            ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if($params){
            $query->andFilterWhere(['like', 'libro.titulo', $params])
            ->orFilterWhere(['like','libro.codigo_barras', $params])
            ->orFilterWhere(['like','autor.nombre', $params])
            ->orFilterWhere(['like','sello.nombre', $params]);
        }

//        $query->andFilterWhere(['=', 'libro.mostrar', 1]);

        return $dataProvider;
    }

    public function novedades(){
        $query = Libro::find()
        ->select('libro.titulo, imagenes.portada, autor.nombre AS autor')
        ->join('INNER JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where(['and',['mostrar'=>1],['novedad'=>1]])->all();
        return $query;
    }

    public function temasbusca($tema){
        return $query = $this->find()
        ->select('libro.*, imagenes.portada, libro.titulo as papu, autor.nombre AS autor')
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->where('libro.mostrar=1')
        ->andWhere('libro.tema_id = '.$tema.'')
        ->orderBy(new Expression('rand()'))
        ->limit(5)
        ->all();
    }
}
?>
