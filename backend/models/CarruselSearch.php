<?php
namespace app\models;


use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Carrusel;
use yii\db\Expression;

class CarruselSearch extends Carrusel {


    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['titulo'], 'string'],
        ];
    }
    public function attributeLabels()
    {
        return[
            'titulo' => 'Título'
        ];
    }

    public function search($params)
    {
        $query = Carrusel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $dataProvider;
    }
}
?>
