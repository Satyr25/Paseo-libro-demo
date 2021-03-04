<?php 

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Correos;

class CorreosForm extends Model
{
    public $email;
    public $noticia;
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['noticia'], 'required', 'message'=>"Correo no puede estar vacío."],
            ['email', 'email', 'message'=>"Correo inválido"],
            ['noticia', 'email', 'message'=>"Correo inválido"],
            ['email', 'unique', 'message'=>"Correo ya registrado"],
            [['email'], 'string', 'max' => 250],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    
    public function saveCorreo($data)
    {
        
        $model = new Correos();
        $model->email = $data;
        if(!$model->save()){
            return false;
        }
        return true;        
    }
}
