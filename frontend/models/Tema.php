<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tema".
 *
 * @property integer $id
 * @property string $nombre
 *
 * @property Libro[] $libros
 */
class Tema extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tema';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibros()
    {
        return $this->hasMany(Libro::className(), ['tema_id' => 'id']);
    }
    
    public function obtenerTema($id){
        $tema = Tema::find()->where(['id' => $id ])->one();
        return $tema;
    }

}
