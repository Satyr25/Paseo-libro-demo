<?php

namespace api\modules\v1\models;

use Yii;

class Paises extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paises';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre','codigo'], 'required'],
            [['codigo'], 'string', 'max' => 5],
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
            'codigo' => 'Codigo',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     *
     */
    public function getEstadosMundo()
    {
        return $this->hasMany(EstadosMundo::className(), ['paises_id' => 'id']);
    }
    
}
