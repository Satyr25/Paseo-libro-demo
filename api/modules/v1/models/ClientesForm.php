<?php
namespace api\modules\v1\models;

use Yii;
use yii\base\Model;

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

    public function rules()
    {
        return [
            // username and password are both required
            [['nombre','apellidos','telefono', 'calle','num_ext','num_int','ciudad',
            'cp','delegacion','colonia','email'], 'required'],
            [['nombre','apellidos','telefono','calle','num_ext','num_int',
            'ciudad','cp','delegacion','colonia',
            'email'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
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
            'delegacion' => 'Delegacion',
            'colonia' => 'Colonia',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'email' => 'Email',
        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    // public function validatePassword($attribute, $params)
    // {
    //     if (!$this->hasErrors()) {
    //         $user = $this->getUser();
    //         if (!$user || !$user->validatePassword($this->password)) {
    //             $this->addError($attribute, 'Incorrect username or password.');
    //         }
    //     }
    // }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    // public function login()
    // {
    //     if ($this->validate()) {
    //         return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    //     }
    //
    //     return false;
    // }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    // protected function getUser()
    // {
    //     if ($this->_user === null) {
    //         $this->_user = User::find()->where('email="'.$this->email.'"')->one();
    //     }
    //
    //     return $this->_user;
    // }
}
