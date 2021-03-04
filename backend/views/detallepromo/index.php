<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

?>
<div class="muestra-libro" id="promo_lib_cod">
        <div class="center">
            <label>Promocion: <?= $promo_code->codigo ?></label>
            <br><br>
            <div class="accionesEB">
                <a href="<?= Url::to(['promociones/index']); ?>"><p style="float: left;"><< Regresar</p></a>
                <p id="agregar-libros" data-promo="<?= $promo_code->id ?>" onclick="agregar(<?= $promo_code->id ?>)">+ Agregar Libros</p>
                <p id="quitar-libros-promo" data-promo="<?= $promo_code->id ?>">- Quitar Libros</p>
            </div> 
            <?php Pjax::begin(['id' => 'pjax-grid-promocio-lib']); ?>
            <?= GridView::widget([
                'id' => 'libros-promo',
                'dataProvider' => $dataprov,
                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                //'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
                //'summary' => "Mostrando {begin} - {end} de {totalCount} libros",
                'summary' => "",
                'emptyText'=>'No se encontraron resultados.',
                'rowOptions'=>function($model){
                    return ['class' => 'view-promo','id'=> $model -> id];
                },
                'columns' => [
                    [
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function ($model, $key, $index, $column) {
                                                     return ['value' => $model['id']];
                                                 }
                    ],
                    [
                        'attribute' => 'codigo_barras',
                        'label' => 'Código de barras',
                        'contentOptions' => ['class' => 'visualDatos datos']
                    ],
                    [
                        'attribute' => 'titulo',
                        'label' => 'Titulo',
                        'contentOptions' => ['class' => 'visualDatos datos']
                    ],
                    [
                        'attribute' => 'pvp',
                        'label' => 'Pvp',
                        'contentOptions' => ['class' => 'visualDatos datos']
                    ],
                    [
                        'attribute' => 'cantidad',
                        'label' => 'Cantidad',
                        'contentOptions' => ['class' => 'visualDatos datos']
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        </div>
</div>

<div class="modal fade bs-example-modal-lg" id="modal-ver-promos-libro" tabindex="-1" role="dialog" style="z-index: 1051;">
            <div class="modal-dialog modal-lg fond classic" role="document">
                <div class="modal-content fond2">
                    <div class="modal-header">
                        <a class="close desplegar-menu desplegado double" data-dismiss="modal"><img src="<?=  Yii::$app->request->BaseUrl.'/imagenes/close_icon.png' ?>"></a>
                    </div>
                    <div class="modal-body" id="ver-promo-libro-placeholder">
                    </div>
                </div> 
            </div>
        </div>

        <div class="modal fade bs-example-modal-lg" id="modal-agregar" tabindex="-1" role="dialog" style="z-index: 1051;">
            <div class="modal-dialog modal-lg fond" role="document">
                <div class="modal-content fond3">
                    <div class="modal-header">
                        <a class="close desplegar-menu desplegado" data-dismiss="modal"><img src="<?=  Yii::$app->request->BaseUrl.'/imagenes/close_icon.png' ?>"></a>
                    </div>
                    <div class="modal-body" id="agregar-lib-add">
                    </div>
                </div> 
            </div>
        </div>

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
