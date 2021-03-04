<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\eventosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="pad-nav-back"></div>
<div class="wrap-nav-buscar">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <p class="etiqueta-buscador"> <?= ucfirst(Yii::$app->controller->id) ?> </p>
            </div>
            <div class="col-md-offset-1 col-md-6">
                <div class="clientes-search col-md-6" id="search-block">
                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>
                    <?= $form->field($filtro, 'nombre')->textInput(['class' => 'input-filtrar', 'placeholder'=>'Buscar'])->label(false) ?>
                    <?= Html::submitButton(Html::img('@web/images/buscador.png', ['class' => 'img-responsive']), ['class' => 'botonBuscar img-buscar-back']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row row-agenda">
        <div class="col-md-12">
            <p class="eventos-titulo">Agenda Cultural </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 row-agregar-evento">
            <?= Html::a('Nuevo Evento', ['nuevo'], ['class' => 'agregar-evento']) ?>
        </div>
    </div>
    <div class="row accionesEB">
        <div class="col-md-12">
            <p id="activar-evento">Activar</p>
            <p id="eliminar-evento">Desactivar</p>        
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 tabla-eventos">
            <?php Pjax::begin(['id' => 'pjax-eventos']) ?>
            <?= GridView::widget([
                'id' => 'eventos-todos',
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model) use ($seleccionados){
                            $checked = false;
                            if($seleccionados && in_array($model->id,$seleccionados)){
                                $checked = true;
                            }
                            return ['value' => $model->id, 'checked' => $checked];
                        },
                    ],
                    [
                        'attribute' => 'fecha',
                        'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                    ],
                    [
                        'attribute' => 'tema',
                        'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                    ],
                    [
                        'attribute' => 'nombre',
                        'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                    ],
                    [
                        'attribute' => 'hora',
                        'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                    ],
                    [
                        'attribute' => 'activo',
                        'contentOptions'=>function($model){
                            return ['class'=>'visualDatos text-center','id'=>$model->id];
                        },
                        'value' => function ($model) {
                            if($model->activo == 1){
                                return 'Si';
                            } else {
                                return 'No';
                            }
                        }
                    ],
                ],
            ]); ?>
            <?php Pjax::end() ?>
        </div>
    </div>
</div>