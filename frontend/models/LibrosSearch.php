<?php
namespace frontend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Libro;
use frontend\models\Busqueda;
use frontend\models\Editorial;

class LibrosSearch extends Libro {

    public function rules()
    {
        return [
            [['interno', 'cantidad', 'paginas', 'tema_id', 'sello_id', 'coleccion_id', 'iva'], 'integer'],
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
        $this->load($params);
        $query = Libro::find()
        ->select('libro.*, 
            imagenes.portada, 
            autor.nombre as autor, 
            editorial.nombre as editorial, 
            editorial.id as editorial_id,
            editorial.logo as logo'
        )
        ->join('LEFT JOIN', 'imagenes', 'imagenes.libro_id = libro.id')
        ->join('INNER JOIN', 'libro_autor', 'libro_autor.libro_id = libro.id')
        ->join('INNER JOIN', 'autor', 'libro_autor.autor_id = autor.id')
        ->join('INNER JOIN', 'tema', 'tema.id = libro.tema_id')
        ->join('INNER JOIN', 'sello', 'sello.id = libro.sello_id')
        ->join('INNER JOIN', 'editorial', 'editorial.id = libro.editorial_id')
        ->where('libro.mostrar = 1')
        ->where('editorial.activo = 1')
        ->where('libro.pvp > 3')
        ->andWhere('cantidad > 0')
        ->andWhere('imagenes.portada IS NOT null')
        ->orderBy('rand()');
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);
            
        if($params['vendidos'] == 1){
//            $query->andWhere('libro.mas_vendido IS NOT null')
            $query->orderBy(['libro.mas_vendido' => SORT_DESC]);
        }
        if($params['promociones'] == 1){
            $query->andWhere('libro.promo IS NOT null');
        }
        if($params['recomendaciones'] == 1){
            $query->andWhere(['libro.recomendacion' => 1]);
        }
        if($params['tema']){
            $query->andFilterWhere(['=', 'tema.id', $params['tema']]);
        }
        if ($params['editorial']){
            $query->andFilterWhere(['=', 'editorial.id', $params['editorial']]); 
        }
        if ($params['buscar']){
            $busqueda_guarda = new Busqueda;
            $busqueda_guarda->termino_busqueda = $params['buscar'];
            $busqueda_guarda->save();
            $query->andFilterWhere([
                'or',
                ['like', 'titulo', $params['buscar']],
                ['like','autor.nombre', $params['buscar']],
                ['=','codigo_barras', $params['buscar']],
                ['=','sello.nombre', $params['buscar']],
                ['=','tema.nombre', $params['buscar']],
                ['=','editorial.nombre', $params['buscar']]
            ]);
        }
        
        if($params['libros-no']){
            foreach($params['libros-no'] as $libro_no){
                $query->andWhere(['!=','libro.id', $libro_no]);
            }
        }
        if (!$this->validate()) {
            
            return $dataProvider;
        }
        return $dataProvider;
    }
}
?>
