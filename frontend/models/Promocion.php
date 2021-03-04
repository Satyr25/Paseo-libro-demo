<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "promocion".
 *
 * @property int $id
 * @property string $codigo
 *
 * @property LibroPromocion[] $libroPromocions
 */
class Promocion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'promocion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo'], 'required'],
            [['codigo'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLibroPromocions()
    {
        return $this->hasMany(LibroPromocion::className(), ['promocion_id' => 'id']);
    }
}
