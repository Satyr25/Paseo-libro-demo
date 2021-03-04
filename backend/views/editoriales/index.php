<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = 'Editoriales';
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
<section id="libros-tabla">
    <div class="container contenedor" id="mostrar-tablas">
        <div class="row">
            <div class="col-md-12">
                <a href="<?=Url::toRoute(['crear'])?>" class="btn-anadir-editorial">Añadir</a>
            </div>
        </div>
        <div class="row accionesEB">
            <div class="col-md-12">
                <p id="activar-editorial">Activar</p>
                <p id="eliminar-editorial">Desactivar</p>                
                <p id="Eliminar">Exportar</p>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
               <?php Pjax::begin(['id' => 'pjax-editoriales']) ?>
                <?= GridView::widget([
                    'id' => 'editoriales-todos',
//                    'id' => 'libros-todos',
                    'dataProvider' => $dataProvider,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    //'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
                    //'summary' => "Mostrando {begin} - {end} de {totalCount} libros",
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
                            'attribute' => 'clave',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                        ],
                        [
                            'attribute' => 'nombre',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                        ],
                        [
                            'attribute' => 'contacto',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                        ],
                        [
                            'attribute' => 'telefono',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                        ],
                        [
                            'attribute' => 'correo',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos text-center','id'=>$model->id];},
                        ],
                        [
                            'attribute' => 'activo',
                            'label' => 'Activo',
                            'contentOptions'=>function($model){
                                return ['class'=>'visualDatos text-center','id'=>$model->id];
                            },
                            'headerOptions' => ['class' => 'text-center'],
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

</section>
