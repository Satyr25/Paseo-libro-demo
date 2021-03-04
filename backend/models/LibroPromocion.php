<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "libro_promocion".
 *
 * @property int $id
 * @property int $libro_id
 * @property int $promocion_id
 *
 * @property Libro $libro
 * @property Promocion $promocion
 */
class LibroPromocion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'libro_promocion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['libro_id', 'promocion_id'], 'required'],
            [['libro_id', 'promocion_id'], 'integer'],
            [['libro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Libro::className(), 'targetAttribute' => ['libro_id' => 'id']],
            [['promocion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Promocion::className(), 'targetAttribute' => ['promocion_id' => 'id']],
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
            'promocion_id' => 'Promocion ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibro()
    {
        return $this->hasOne(Libro::className(), ['id' => 'libro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPromocion()
    {
        return $this->hasOne(Promocion::className(), ['id' => 'promocion_id']);
    }
}
