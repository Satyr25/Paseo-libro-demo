<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property string $nombre
 * @property string $tema
 * @property string $presentador
 * @property int $fecha
 * @property string $hora
 * @property string $imagen
 * @property string $descripcion
 * @property int $categoria_id
 *
 * @property Categoria $categoria
 */
class Eventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'categoria_id'], 'required'],
            [['fecha', 'categoria_id'], 'integer'],
            [['descripcion'], 'string'],
            [['nombre', 'tema', 'presentador', 'imagen'], 'string', 'max' => 255],
            [['hora'], 'string', 'max' => 45],
            [['categoria_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['categoria_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'tema' => 'Tema',
            'presentador' => 'Presentador',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'imagen' => 'Imagen',
            'descripcion' => 'Descripcion',
            'categoria_id' => 'Categoria ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['id' => 'categoria_id']);
    }
    
    public function proximos(){
        $time = new \DateTime('now');
        $today = $time->format('U');  
        return $this->find()
        ->select('eventos.*')
        ->where(['>=', 'fecha', $today])
        ->all();
    }
}
