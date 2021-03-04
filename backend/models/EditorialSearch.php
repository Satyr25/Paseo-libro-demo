<?php
namespace backend\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Editorial;
use yii\db\Expression;

class EditorialSearch extends Editorial {


    public function rules()
    {
        return [
            [['activo'], 'integer'],
            [['clave', 'nombre', 'contacto', 'telefono', 'correo', 'logo', 'activo'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'clave' => 'Clave',
            'nombre' => 'Nombre Editorial', 
            'contacto' => 'Contacto',
            'telefono' => 'Telefono',
            'correo' => 'Correo',
            'activo' => 'Activo',
        ];
    }

    public function search($params)
    {
        $query = Editorial::find();
//            ->where('activo = 1');
      
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);
        
        if($params['EditorialSearch']['nombre']){
            $query->andFilterWhere(['like', 'nombre', $params['EditorialSearch']['nombre']])
            ->orFilterWhere(['like','clave', $params['EditorialSearch']['nombre']])
            ->orFilterWhere(['like','contacto', $params['EditorialSearch']['nombre']]);
        }

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
       
        $query->andFilterWhere(['=', 'libro.mostrar', 1]);

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
