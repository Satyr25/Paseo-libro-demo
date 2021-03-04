<?php
namespace api\modules\v1\models;
use Yii;
use yii\base\Model;

class ContactoForm extends Model
{
    public $name;
    public $email;
    public $password;
    public $nombre;
    public $correo;
    public $mensaje;
    public $archivo;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name','mensaje','archivo'], 'string'],
            ['correo', 'email'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mensaje'=>['Mensaje:']
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$this->correo => $this->name])
            ->setSubject("Contacto Lectorum")
            ->setHtmlBody('<b>Nombre: </b> '.$this->name.'<br>'.
            '<b>Correo: </b>'.$this->correo.'<br>'.
            '<b>Mensaje: </b><br>'.$this->mensaje.'<br>'.
            '<b>Archivo: </b>'.$this->archivo.'<br>')
            ->send();
    }
}
