<?php

namespace backend\models;

use Yii;

class EstadosMundo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estados_mundo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estadonombre'], 'required'],
            [['estadonombre'], 'string', 'max' => 250],
            [['paises_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['paises_id' => 'id']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'paises_id' => 'Paises ID',
            'estadonombre' => 'Nombre Estado',
        ];
    }

   public function getPaises()
    {
        return $this->hasOne(Paises::className(), ['id' => 'paises_id']);
    }
    public function getEstadosPais($provid) { 
     
        //var_dump('providodo: '.$provid);exit;
        $data= EstadosMundo::find() 
         ->where(['paises_id'=>$provid]) 
         ->select(['id','estadonombre AS name' ])->asArray()->all(); 

        return $data; 

    } 
}
