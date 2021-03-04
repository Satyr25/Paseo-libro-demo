<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "correos".
 *
 * @property int $id
 * @property string $email
 */
class Correos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'correos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            ['email', 'email', 'message'=>"Correo inválido"],
            ['email', 'unique', 'message'=>"Correo ya registrado"],
            [['email'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
        ];
    }
}