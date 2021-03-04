<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Clientes;
use frontend\models\Usuario;

class ClientesForm extends Model
{
    public $usuario_id;
    public $paises_id;
    public $estados_mundo_id;
    public $nombre;
    public $apellidos;
    public $telefono;
    public $calle;
    public $num_ext;
    public $num_int;
    public $ciudad;
    public $cp;
    public $delegacion;
    public $colonia;
    public $email;
    public $password;
    public $publicidad;

    public function rules()
    {
        return [
            // username and password are both required
            [['nombre','apellidos','telefono', 'calle','num_ext','ciudad',
            'cp','delegacion','colonia','email', 'paises_id', 'estados_mundo_id'], 'required', 'message' => 'Por favor ingresa {attribute}'],
            [['nombre','apellidos','calle','num_ext','num_int',
            'ciudad','delegacion','colonia',
            'email'], 'string'],
            [['created_at', 'updated_at', 'publicidad'], 'integer'],
            [['telefono'],'string','length' => [10, 10],
            'tooLong' => 'Telefono debería contener máximo 10 números.',
            'tooShort' => 'Telefono debería contener al menos 10 números.'],
            [['cp'],'string', 'length' => [5, 5],
            'tooLong' => 'Codigo Postal debería contener como máximo 5 números.',
            'tooShort' => 'Codigo Postal debería contener 5 números.'],
        ];
    }

   public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'paises_id' => 'Paises ID',
            'estados_mundo_id' => 'Estados ID',
            'nombre' => 'Nombre',
            'apellidos' => 'Apellidos',
            'telefono' => 'Telefono',
            'calle' => 'Calle',
            'num_ext' => 'Número Exterior',
            'num_int' => 'Número Interior',
            'ciudad' => 'Ciudad',
            'cp' => 'Codigo Postal',
            'delegacion' => 'Delegación',
            'colonia' => 'Colonia',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'email' => 'Email',
        ];
    }

    public function agregarCli(){
        $post = Yii::$app->request->post();
        $post = $post['ClientesForm'];
        $exist = Clientes::findOne(['usuario_id' => $post['usuario_id']]);
        if($exist){
            $name = Usuario::findOne(['id'=> $post['usuario_id']]);
            $cliente = $exist;
            $cliente->nombre = ucwords($name->nombre);
            $cliente->apellidos = ucwords($name->ap_paterno);
            $cliente->telefono = $post['telefono'];
            $cliente->calle = $post['calle'];
            $cliente->num_ext = $post['num_ext'];
            $cliente->num_int = $post['num_int'];
            $cliente->ciudad = $post['ciudad'];
            $cliente->paises_id = $post['paises_id'];
            $cliente->estados_mundo_id = $post['estados_mundo_id'];
            $cliente->cp = $post['cp'];
            $cliente->delegacion = $post['delegacion'];
            $cliente->colonia = $post['colonia'];
            $cliente->email = $post['email'];
            if($cliente->save()){
                return true;
            }
        }else{
            $name = Usuario::findOne(['id'=> $post['usuario_id']]);
            $cliente = new Clientes();
            $cliente->nombre = ucwords($name->nombre);
            $cliente->apellidos = ucwords($name->ap_paterno);
            $cliente->telefono = $post['telefono'];
            $cliente->calle = $post['calle'];
            $cliente->num_ext = $post['num_ext'];
            $cliente->num_int = $post['num_int'];
            $cliente->ciudad = $post['ciudad'];
            $cliente->paises_id = $post['paises_id'];
            $cliente->estados_mundo_id = $post['estados_mundo_id'];
            $cliente->cp = $post['cp'];
            $cliente->delegacion = $post['delegacion'];
            $cliente->colonia = $post['colonia'];
            $cliente->email = $post['email'];
            $cliente->usuario_id = $post['usuario_id'];
            if($cliente->save()){
                return true;
            }
        }
        print_r($cliente->getErrors());exit;
    }

    public function agregarCliente(){
        $post = Yii::$app->request->post();
        $post = $post['ClientesForm'];
        $exist = Clientes::findOne(['usuario_id' => $post['usuario_id']]);
        if($exist){
            $name = Usuario::findOne(['id'=> $post['usuario_id']]);
            $name->nombre = $post['nombre'];
            $name->ap_paterno = $post['apellidos'];
            $name->ap_materno = 'NULL';
            $cliente = $exist;
            $cliente->nombre = ucwords($name->nombre);
            $cliente->apellidos = ucwords($name->ap_paterno);
            $cliente->telefono = $post['telefono'];
            $cliente->calle = $post['calle'];
            $cliente->num_ext = $post['num_ext'];
            $cliente->num_int = $post['num_int'];
            $cliente->ciudad = $post['ciudad'];
            $cliente->paises_id = $post['paises_id'];
            $cliente->estados_mundo_id = $post['estados_mundo_id'];
            $cliente->cp = $post['cp'];
            $cliente->delegacion = $post['delegacion'];
            $cliente->colonia = $post['colonia'];
            $cliente->email = $post['email'];
            if($cliente->save()){
                return true;
            }
        }else{
            $name = Usuario::findOne(['id'=> $post['usuario_id']]);
            $name->nombre = $post['nombre'];
            $name->ap_paterno = $post['apellidos'];
            $name->ap_materno = 'NULL';
            $cliente = new Clientes();
            $cliente->nombre = $post['nombre'];
            $cliente->apellidos = $post['apellidos'];
            $cliente->telefono = $post['telefono'];
            $cliente->calle = $post['calle'];
            $cliente->num_ext = $post['num_ext'];
            $cliente->num_int = $post['num_int'];
            $cliente->ciudad = $post['ciudad'];
            $cliente->paises_id = $post['paises_id'];
            $cliente->estados_mundo_id = $post['estados_mundo_id'];
            $cliente->cp = $post['cp'];
            $cliente->delegacion = $post['delegacion'];
            $cliente->colonia = $post['colonia'];
            $cliente->email = $post['email'];
            $cliente->usuario_id = $post['usuario_id'];
            if($cliente->save() && $name->save()){
                return true;
            }
        }
        print_r($cliente->getErrors());exit;
    }
    
    public function actualizarCliente(){
        $cliente = Clientes::find()->where(['usuario_id' => Yii::$app->user->identity->id])->one();
        $cliente->telefono = $this->telefono;
        $cliente->calle = $this->calle;
        $cliente->num_ext = $this->num_ext;
        $cliente->num_int = $this->num_int;
        $cliente->cp = $this->cp;
        $cliente->ciudad = $this->ciudad;
        $cliente->paises_id = $this->paises_id;
        $cliente->estados_mundo_id = $this->estados_mundo_id;
        $cliente->delegacion = $this->delegacion;
        $cliente->colonia = $this->colonia;
        if(!$cliente->save()){
            return false;
        } else {
            return true;
        }
    }
}
