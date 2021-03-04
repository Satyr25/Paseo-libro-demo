<?php
namespace api\modules\v1\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Libro;

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
        $query = Libro::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }

        $query->orFilterWhere(['like', 'libro.titulo', $this->titulo])
            ->orFilterWhere(['like','libro.codigo_barras',$this->titulo])
            ->orFilterWhere(['like','libro.isbn',$this->titulo])
            ;

        return $dataProvider;
    }
}
?>
