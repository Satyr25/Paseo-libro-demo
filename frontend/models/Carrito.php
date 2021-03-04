<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


class Carrito extends \yii\db\ActiveRecord
{
    public $cantidad;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carrito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['cookie_id', 'usuario_id', 'total'], 'required'],
            [['usuario_id', 'created_at', 'updated_at'], 'integer'],
            [['total'], 'number'],
            [['cookie_id'], 'string', 'max' => 45],

            //[['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function behaviors(){
        return [
            // TimestampBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
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
            'cookie_id' => 'Cookie ID',
            'total' => 'Total',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['id' => 'usuario_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductoCarritos()
    {
        return $this->hasMany(LibroCarrito::className(), ['carrito_id' => 'id']);
    }

    public function idCarrito($cookie, $identificador){
        if($cookie){
            $carrito = Carrito::find()->where('cookie_id="'.$identificador.'"')->one();
        }else{
            $carrito = Carrito::find()->where('usuario_id="'.$identificador.'"')->one();
        }
        if($carrito){
            return $carrito->id;
        }
        if($cookie){
            $this->cookie_id = $identificador;
        }else{
            $this->usuario_id = $identificador;
        }
        if(!$this->save())
            return false;
        return $this->id;

    }

    /*public function guardarLibro($id)
    {
        $this->libro_id = $id;
        $this->cantidad = "0";
        $this->usuario_id = "1";

        $validaLibro = $this->save();

        if(!$validaLibro){
            var_dump(($this->getErrors()));exit;
            return false;
        }

        return true;
    }

    public function borrarLibro($id)
    {
        $libro = Carrito::find()
        ->where(['id' => $id])
        ->one();

        $validaBorrar = $libro -> delete();
        if(!$validaBorrar){
            return false;
        }

        return true;
    }*/

   
}
