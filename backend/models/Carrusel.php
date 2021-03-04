<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "carrusel".
 *
 * @property int $id
 * @property string $imagen
 * @property string $titulo
 * @property int $created_at
 * @property int $updated_at
 */
class Carrusel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrusel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagen', 'titulo'], 'required'],
            [['status'], 'integer'],
            [['imagen'], 'string', 'max' => 512],
            [['url'], 'string', 'max' => 512],
            [['titulo'], 'string', 'max' => 256],
        ];
    }

    public function behaviors(){
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'imagen' => 'Imagen',
            'titulo' => 'Titulo',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
