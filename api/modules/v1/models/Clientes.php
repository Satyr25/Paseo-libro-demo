<?php

namespace api\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;


class Clientes extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'apellidos', 'telefono', 'calle', 'num_ext', 'ciudad', 'cp', 'delegacion', 'colonia', 'email'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['nombre', 'apellidos', 'telefono', 'calle', 'num_ext', 'num_int', 'ciudad', 'cp', 'delegacion', 'colonia', 'email'], 'string'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['paises_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['paises_id' => 'id']],
            [['estados_mundo_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstadosMundo::className(), 'targetAttribute' => ['estados_mundo_id' => 'id']],
        ];
    }
    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
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
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'usuario_id']);
    }
    public function getPaises()
    {
        return $this->hasOne(Paises::className(), ['id' => 'paises_id']);
    }
    public function getEstadosMundo()
    {
        return $this->hasOne(EstadosMundo::className(), ['id' => 'estados_mundo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductoCarritos()
    {
        return $this->hasMany(LibroCarrito::className(), ['carrito_id' => 'id']);
    }
   
}
