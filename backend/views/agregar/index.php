<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

?>
  
<div class="muestra-libro" id="agregar-lib" style="margin-top: 7%;">

        <div class="center">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
                'id' => 'w3',
                'options'=>['style'=>'margin-bottom: 25px'],
            ]); ?>
            <?= $form->field($filtro, 'titulo')->textInput(['class' => 'input-filtrar', 'placeholder'=>'Buscar'])->label(false) ?>
            <?= Html::submitButton('', ['class' => 'botonBuscar modal-buscar']) ?>

            <?php ActiveForm::end(); ?>
            <div class="accionesEB">
                <a href="<?= Url::to(['detallepromo/', 'codigo' => ($id_promo)]); ?>"><p style="float: left;"><< Regresar</p></a>
                <p id="add-lib" data-promo="<?= $id_promo ?>">Agregar</p>
            </div> 
            <?php Pjax::begin(['id' => 'pjax-grid-promocio-lib']); ?>
            <?= GridView::widget([
                'id' => 'agregar-lib',
                'dataProvider' => $dataProvider,
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
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                    [
                        'attribute' => 'titulo',
                        'label' => 'Titulo',
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                    [
                        'attribute' => 'pvp',
                        'label' => 'Pvp',
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                    [
                        'attribute' => 'cantidad',
                        'label' => 'Cantidad',
                        'contentOptions' => ['class' => 'visualDatos']
                    ],
                ],
            ]); ?>
        <?php Pjax::end(); ?>
        <div class="accionesEB">
            <p id="add-lib" data-promo="<?= $id_promo ?>">Agregar</p>
        </div> 
        </div>
</div>
