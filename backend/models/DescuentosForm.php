<?php 

namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\Descuentos;

class DescuentosForm extends Model
{
    public $activo;
    public $porcentaje;
    public $codigo;
    public $global;

    public function rules()
    {
        return [
            [['codigo', 'porcentaje'], 'required'],
            [['porcentaje'], 'number'],
            [['activo', 'global'], 'integer'],
            [['codigo'], 'string', 'max' => 255],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'procentaje' => 'Porcentaje',
            'activo' => 'Activo',
            'global' => 'Cupón Global'
        ];
    }

    
    public function saveDescuento()
    {
        $model = new Descuentos();
        $model->codigo = Yii::$app->request->post('codigo');
        $model->porcentaje = Yii::$app->request->post('porcentaje');
        $model->activo = Yii::$app->request->post('activo');
        $model->global = Yii::$app->request->post('cupon_glob');
        $model->save();
    }

    public function editarDescuentos(){
        $model = Descuentos::findOne(['id' => Yii::$app->request->post('desc_id')]);
        $model->codigo = Yii::$app->request->post('codigo');
        $model->porcentaje = Yii::$app->request->post('porcentaje');
        $model->activo = Yii::$app->request->post('activo');
        $model->global = Yii::$app->request->post('cupon_glob');
        $model->save();
    }
}
