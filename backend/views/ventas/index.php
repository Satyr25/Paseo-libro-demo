<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$this->title = 'Ventas';
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
                    <?= Html::submitButton(Html::img('@web/images/buscador.png', ['class' => 'img-responsive']), ['class' => 'botonBuscar img-buscar-back']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="modal fade bs-example-modal-lg" id="modal-ver-ventas" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg fond" role="document">
                        <div class="modal-content fond2">
                            <div class="modal-header">
                                <a class="close desplegar-menu desplegado" data-dismiss="modal"><img src="<?=  Yii::$app->request->BaseUrl.'/imagenes/close_icon.png' ?>"></a>
                            </div>
                            <div class="modal-body" id="ver-ventas-placeholder">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="ventas-tabla">
    <div class="container contenedor" id="mostrar-tablas">
        <div class="row accionesEB">
            <div class="col-md-6">
                <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
            </div>
            <div class="col-md-6">
                <p id="Exportar">Exportar</p>
            </div>
        </div>
        <?php if($editorial){ ?>
            <div class="row row-datos-editorial">
                <div class="col-md-2">
                    <?= Html::img('@web/images/'.$editorial->logo, ['class' => 'img-responsive']) ?>
                </div>
                <div class="col-md-4">
                    <p class="editorial-datos-titulo">Contacto</p>
                    <p class="editorial-datos"><?= $editorial->contacto ?></p>
                    <p class="editorial-datos-titulo">Telefono</p>
                    <p class="editorial-datos"><?= $editorial->telefono ?></p>
                    <p class="editorial-datos-titulo">Correo</p>
                    <p class="editorial-datos"><?= $editorial->correo ?></p>
                </div>
                <div class="col-md-6">
                    <?= Html::a('Ventas', ['ventas/index', 'id' => $editorial->id], ['class' => 'editorial-enlaces']) ?>
                    <?= Html::a('Devoluciones', ['ventas/index', 'id' => $editorial->id], ['class' => 'editorial-enlaces']) ?>
                    <?= Html::a('Pagos', ['ventas/index', 'id' => $editorial->id], ['class' => 'editorial-enlaces']) ?>
                </div>
            </div>
        <?php } ?>
        <div class="row-datos-ventas">
            <div class="col-md-2">
                <p class="ventas-monto-texto">Monto Total del:</p>
            </div>
            <div class="col-md-5">
                <?php if (Yii::$app->request->get('PedidoSearch')['inicio']){ 
                    $inicio = Yii::$app->request->get('PedidoSearch')['inicio'];
                } else {
                    $inicio = date('d-m-Y', strtotime("-1 months"));
                } 
                if (Yii::$app->request->get('PedidoSearch')['fin']){ 
                    $fin = Yii::$app->request->get('PedidoSearch')['fin'];
                } else {
                    $fin = date('d-m-Y', time());
                } ?>
                <?=
                    DatePicker::widget([
                        'id' => 'ventas-rango',
                        'name' => 'from_date',
                        'value' => $inicio,
                        'type' => DatePicker::TYPE_RANGE,
                        'language' => 'es',
//                        'id2' => 'to_date',
                        'name2' => 'to_date',
                        'value2' => $fin,
//                        'language' => 'es',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]);
                ?>
            </div>
            <div class="col-md-1">
                <p class="ventas-monto-texto2">Editorial:</p>
            </div>
            <div class="col-md-2">
                <?php if (Yii::$app->request->get('PedidoSearch')['id']){ ?>
                    <?= Html::dropDownList('Editoriales', '', $editoriales, ['prompt' => 'Todas', 'class' => 'editoriales-drop', 'options' => [Yii::$app->request->get('PedidoSearch')['id'] => ['selected' => true]]]); ?>
                <?php } else { ?>
                    <?= Html::dropDownList('Editoriales', '', $editoriales, ['prompt' => 'Todas', 'class' => 'editoriales-drop']); ?>
                <?php } ?>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" id="ventas-consultar">Consultar</button>
            </div>
<!--
            <div class="col-md-2">
                <p class="ventas-monto-numero"></p>
            </div>
-->
        </div>
        <div id="total-ventas">
            <span id="label-total">Total: </span>
            <span id="cantidad-total">$<?= number_format($total,2) ?></span>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= GridView::widget([
                    'id' => 'ventas-todos',
                    'dataProvider' => $dataProvider,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'summary' => "",
                    'emptyText'=>'No se encontraron resultados.',
                    'rowOptions'=>function($model){
                        return [
                            'onclick' => 'location.href="'.Url::to(['ventas/ver', 'id' => $model->id ]).'"',
                        ];
                    },
                    'columns' => [
//                        [
//                            'class' => 'yii\grid\CheckboxColumn',
//                            'checkboxOptions' => function ($model, $key, $index, $column) {
//                                return ['value' => $model['id']];
//                            }
//                        ],
                        [
                            'label' => 'No Pedido',
                            'attribute' => 'numero_pedido',
                            'contentOptions' => ['class' => 'visualDatos']
                        ],
                        [
                            'label' => 'Fecha',
                            'attribute' => 'created_at',
                            'contentOptions' => ['class' => 'visualDatos'],
                            'format' => ['date', 'php:d/m/Y']
                        ],
                        [
                            'label' => 'Cliente',
                            'value' => function($model) { return $model->nombre  . " " . $model->apellidos ;},
                            'contentOptions' => ['class' => 'visualDatos']
                        ],
                        [
                            'label' => 'Cantidad',
                            'value' => function($model) use($editorial){
                                if($editorial){
                                    return $model->getCantidad($model->id,$editorial->id);
                                }elseif(Yii::$app->user->identity->rol_id == 2){
                                    return $model->getCantidad($model->id,Yii::$app->user->identity->editorial_id);
                                }
                                return $model->getCantidad($model->id);
                            },
                            'contentOptions' => ['class' => 'visualDatos'],
                        ],
                        [
                            'label' => 'Monto',
                            'value' => function($model) use($editorial){
                                if($editorial){
                                    return $model->getMonto($model->id,$editorial->id);
                                }elseif(Yii::$app->user->identity->rol_id == 2){
                                    return $model->getMonto($model->id,Yii::$app->user->identity->editorial_id);
                                }
                                return $model->getMonto($model->id);
                                // return ($model->costo_total);
                                // return ($model->costo_total - $model->costo_envio);
                            },
                            'format' => ['currency', ' $ '],
                            'contentOptions' => ['class' => 'visualDatos'],
                        ],
                        [
                            'label' => 'Estatus',
                            'attribute' => 'estado',
                            'contentOptions' => ['class' => 'visualDatos']
                        ],
                        [
                            'label' => 'No Seguimiento',
                            'attribute' => 'tracking',
                            'contentOptions' => ['class' => 'visualDatos']
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</section>
