<?php
use backend\models\Editorial;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = 'Libros';
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
                    <?= $form->field($filtro, 'titulo')->textInput(['class' => 'input-filtrar', 'placeholder'=>'Buscar'])->label(false) ?>
                    <?= $form->field($filtro, 'activados')->checkbox(['checked' => 'checked', 'uncheck' => null])->label(''); ?>
                    <?= Html::submitButton(Html::img('@web/images/buscador.png', ['class' => 'img-responsive']), ['class' => 'botonBuscar img-buscar-back']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="libros-tabla">
    <div class="container contenedor" id="mostrar-tablas">
        <div class="row accionesEB">
            <div class="col-md-12">
            <?php if(Yii::$app->user->identity->rol_id == '2'){ ?>
                <p id="Activar">Activar</p>
                <p id="Eliminar">Desactivar</p>
            <?php } ?>
            <?php if(Yii::$app->user->identity->rol_id == '1'){ ?>
                <!-- <p id="Activar-novedad">Activar en Carrusel</p>
                <p id="Eliminar-novedad">Desactivar en Carrusel</p>   -->
                <p id="Activar-recomendacion">Activar Recomendación</p>
                <p id="Eliminar-recomendacion">Desactivar Recomendación</p>
            <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
               <?php Pjax::begin(['id' => 'pjax-detalles']) ?>
                <?= GridView::widget([
                    'id' => 'libros-todos',
                    'dataProvider' => $dataProvider,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'summary' => "",
                    'emptyText'=>'No se encontraron resultados.',
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
                            'attribute' => 'codigo_barras',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                            'headerOptions' => ['class' => 'text-center']
                        ],
                        [
                            'attribute' => 'titulo',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id, 'style' => 'max-width: 550px; overflow:hidden;'];},
                            'headerOptions' => ['class' => 'text-center']
                        ],
                        [
                            'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                            'label' => 'Editorial',
                            'value' => function ($model) {
                                $editorial = Editorial::findOne($model->editorial_id);
                                return $editorial->nombre;
                            },
                            'headerOptions' => ['class' => 'text-center']
                        ],
                        [
                            'attribute' => 'pvp',
                            'label' => 'PVP',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                            'headerOptions' => ['class' => 'text-center']
                        ],
                        [
                            'attribute' => 'cantidad',
                            'label' => 'Existencias',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                            'headerOptions' => ['class' => 'text-center']
                        ],
                        [
                            'attribute' => 'mostrar',
                            'label' => 'Activo',
                            'contentOptions'=>function($model){
                                return ['class'=>'visualDatos text-center','id'=>$model->id];
                            },
                            'headerOptions' => ['class' => 'text-center'],
                            'value' => function ($model) {
                                if($model->mostrar == 1){
                                    return 'Si';
                                } else {
                                    return 'No';
                                }
                            }
                        ],
                        // [
                        //     'attribute' => 'novedad',
                        //     'label' => 'Carrusel',
                        //     'contentOptions'=>function($model){
                        //         return ['class'=>'visualDatos text-center','id'=>$model->id];
                        //     },
                        //     'headerOptions' => ['class' => 'text-center'],
                        //     'value' => function ($model) {
                        //         if($model->novedad == 1){
                        //             return 'Si';
                        //         } else {
                        //             return 'No';
                        //         }
                        //     },
                        //     'visible' => (Yii::$app->user->identity->rol_id == '1')
                        // ],
                        [
                            'attribute' => 'recomendacion',
                            'label' => 'Recomendación',
                            'contentOptions'=>function($model){
                                return ['class'=>'visualDatos text-center','id'=>$model->id];
                            },
                            'headerOptions' => ['class' => 'text-center'],
                            'value' => function ($model) {
                                if($model->recomendacion == 1){
                                    return 'Si';
                                } else {
                                    return 'No';
                                }
                            },
                            'visible' => (Yii::$app->user->identity->rol_id == '1')
                        ],
                    ],
                ]); ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>

</section>
