<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = 'Libros';
?>

<section id="inicio-libros">
    <!-- <div class="alert-placeholder"></div> -->
            <h1 class="titulo-pag">Descuentos</h1>
            <div class="clientes-search col-md-6" id="search-block">

                    <?php $form = ActiveForm::begin([
                        'action' => ['index'],
                        'method' => 'get',
                    ]); ?>
                    <?= $form->field($filtro, 'codigo')->textInput(['class' => 'input-filtrar', 'placeholder'=>'Buscar'])->label(false) ?>
                    <?= Html::submitButton('', ['class' => 'botonBuscar']) ?>

                    <?php ActiveForm::end(); ?>
            </div>
</section>

<section id="descuentos-tabla">
    <div class="contenedor" id="mostrar-tablas">
        <div class="accionesEB">
            <p id="desc-crear">Crear Descuento</p>
            <p id="desc-eliminar">Desactivar Descuento</p>
        </div>
            <?php Pjax::begin(['id' => 'pjax-grid-descuentos']); ?>
            <?= GridView::widget([
                'id' => 'descuentos-todos',
                'dataProvider' => $dataProvider,
                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                //'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
                //'summary' => "Mostrando {begin} - {end} de {totalCount} libros",
                'summary' => "",
                'emptyText'=>'No se encontraron resultados.',
                'rowOptions'=>function($model){
                        return ['class' => 'view-descuentos','id'=> $model->id];
                },
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                                                     return ['value' => $model['id']];
                                                 }
                    ],
                    [
                        'attribute' => 'codigo',
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                    [
                        'attribute' => 'porcentaje',
                        'value' => function($model) { return $model->porcentaje / 100 ;},
                        'format' => ['percent', 'decimals' => 1],
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                    [
                        'attribute' => 'activo',
                        'label' => 'Estatus',
                        'value' => function($model) { if($model->activo == 1 && $model->global == 0){ return 'Activo';}elseif($model->activo == 1 && $model->global == 1){ return 'Global';}else{return 'Inactivo';} ;},
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
    </div>

</section>

<div class="modal fade bs-example-modal-lg" id="modal-ver-promos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg fond classic" role="document">
        <div class="modal-content fond2">
            <div class="modal-header">
                <a class="close desplegar-menu desplegado" data-dismiss="modal"><img src="<?=  Yii::$app->request->BaseUrl.'/imagenes/close_icon.png' ?>"></a>
            </div>
            <div class="modal-body" id="ver-promo-placeholder">
            </div>
        </div> 
    </div>
</div>
