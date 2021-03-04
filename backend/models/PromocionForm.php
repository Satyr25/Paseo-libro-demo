<?php 

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\LibroPromocion;
use backend\models\Promocion;

class PromocionForm extends Model
{
    public $promocion_id;
    public $libro_id;
    public $codigo;

    public function rules()
    {
        return [
            [['libro_id', 'promocion_id'], 'required'],
            [['libro_id', 'promocion_id'], 'integer'],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
            [['promocion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promocion::className(), 'targetAttribute' => ['promocion_id' => 'id']],
            [['codigo'], 'required'],
            [['codigo'], 'string', 'max' => 30],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'libro_id' => 'Libro ID',
            'promocion_id' => 'Promocion ID', 
        ];
    }

    
    public function savePromocion()
    {
        $model = new Promocion();
        $model->codigo = Yii::$app->request->post('codigo');
        $model->save();
    }

    public function editarPromocion(){
        $model = Promocion::find()->where('promocion.id ='.Yii::$app->request->post('codigo'))->one();
        $model->codigo = Yii::$app->request->post('renombrar');
        $model->save();
    }
}
