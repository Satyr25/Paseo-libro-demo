<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "libro_autor".
 *
 * @property int $id
 * @property int $libro_id
 * @property int $autor_id
 *
 * @property Autor $autor
 * @property Libro $libro
 */
class LibroAutor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'libro_autor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['libro_id', 'autor_id'], 'required'],
            [['libro_id', 'autor_id'], 'integer'],
            [['autor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Autor::className(), 'targetAttribute' => ['autor_id' => 'id']],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'libro_id' => 'Libro ID',
            'autor_id' => 'Autor ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutor()
    {
        return $this->hasOne(Autor::className(), ['id' => 'autor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }
}