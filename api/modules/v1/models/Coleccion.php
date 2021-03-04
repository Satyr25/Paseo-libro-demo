<?php

namespace api\modules\v1\models;

use Yii;

/**
 * This is the model class for table "coleccion".
 *
 * @property integer $id
 * @property string $nombre
 *
 * @property Libro[] $libros
 */
class Coleccion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coleccion';
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
    public function getLibros()
    {
        return $this->hasMany(Libro::className(), ['coleccion_id' => 'id']);
    }
}
