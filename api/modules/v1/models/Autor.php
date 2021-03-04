<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "autor".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $apellidos
 *
 * @property LibroAutor[] $libroAutors
 */
class Autor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'autor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string', 'max' => 128]
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
    public function getLibroAutors()
    {
        return $this->hasMany(LibroAutor::className(), ['autor_id' => 'id']);
    }

    public function getNames()
    {
        return $this->find()->select(['nombre'])->orderBy('nombre ASC')->column();
    }

    public function getId($nombre)
    {
        $autor = $this->find()->select(['id'])->where(['nombre' => $nombre])->one();
        if($autor){
            return $autor->id;
        }else{
            $this->nombre = $nombre;
            if($this->save()){
                return $this->id;
            }
            return false;
        }
    }
}
