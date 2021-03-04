<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carrusel".
 *
 * @property int $id
 * @property string $imagen
 * @property string $titulo
 * @property int $status
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
            [['imagen', 'titulo', 'status', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['imagen'], 'string', 'max' => 512],
            [['url'], 'string', 'max' => 512],
            [['titulo'], 'string', 'max' => 256],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
