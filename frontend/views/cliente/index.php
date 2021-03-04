<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
?>

<div class="container container-cliente">
    <div class="row">
        <div class="col-sm-7">
            <p class="titulo-cliente" >Bienvenido <?= ucfirst(Yii::$app->user->identity->nombre).' '.ucfirst(Yii::$app->user->identity->ap_paterno) ?></p>
        </div>
        <div class="col-sm-5">
            <?= Html::beginForm(['/site/logout'], 'post') ?>
            <?= Html::submitButton('Cerrar sesión'.Html::img('@web/images/iconos/cerrarSesion.png', ['class' => 'img-responsive img-back-nav']),['class' => 'btn btn-link logout-cliente'])?>
            <?= Html::endForm() ?>
        </div>
    </div>
    <div class="row row-subtitulo">
        <div class="col-sm-12">
            <p class="subtitulo-cliente contacto" id="subtitulo-cliente-contacto">Datos de Contacto <?= Html::img('@web/images/iconos/PlecaP.png', ['id' => 'usuario-desplegar-contacto']);?><?= Html::img('@web/images/iconos/plecaD.png', ['id' => 'usuario-cerrar-contacto']); ?></p>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'form-resignup', 'action' => ['cliente/resignup']]) ?>
        <div class="col-sm-6 formularios-cliente">
            <?= $form->field($resignup, 'nombre')->textInput(['class' => 'input-resignup-contacto', 'value' => ucfirst(Yii::$app->user->identity->nombre), 'disabled' => true])->label(false) ?>            
        </div>
        <div class="col-sm-3 formularios-cliente">
            <?= $form->field($resignup, 'apellidoPaterno')->textInput(['class' => 'input-resignup-contacto', 'value' => ucfirst(Yii::$app->user->identity->ap_paterno), 'disabled' => true])->label(false) ?>
        </div>
        <div class="col-sm-3 formularios-cliente">
            <?= $form->field($resignup, 'apellidoMaterno')->textInput(['class' => 'input-resignup-contacto', 'value' => ucfirst(Yii::$app->user->identity->ap_materno), 'disabled' => true])->label(false) ?>
            <?= Html::img('@web/images/iconos/editar.png', ['class' => 'img-responsive cliente-editar-form', 'id' => 'editar-usuario']);  ?>
        </div>
        <div class="col-sm-offset-4 col-sm-4 row-actualizar-usuario">
            <?= Html::submitButton('Guardar', ['class' => 'btn-actualizar-usuario', 'disabled' => true]) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row row-subtitulo">
        <div class="col-sm-12">
            <p  class="subtitulo-cliente" id="subtitulo-cliente-envio">Datos de Envío <?= Html::img('@web/images/iconos/PlecaP.png', ['id' => 'usuario-desplegar-envio']);?><?= Html::img('@web/images/iconos/plecaD.png', ['id' => 'usuario-cerrar-envio']); ?></p>
        </div>
        <?php $form = ActiveForm::begin(['id' => 'form-resignup2', 'action' => ['cliente/actualizar']])?>
        <div class="row row-formulario-cliente">
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Teléfono:</span>
                <?= $form->field($clienteForm, 'telefono')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->telefono, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?>  
            </div>
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Calle:</span>
                <?= $form->field($clienteForm, 'calle')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->calle, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
            <div class="col-sm-1 formularios-cliente">
                <span class="label-cliente">Ext:</span>
                <?= $form->field($clienteForm, 'num_ext')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->num_ext, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
            <div class="col-sm-1 formularios-cliente">
                <span class="label-cliente">Int:</span>
                <?= $form->field($clienteForm, 'num_int')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->num_int, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
            <div class="col-sm-1 formularios-cliente">
                <span class="label-cliente">CP:</span>
                <?= $form->field($clienteForm, 'cp')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->cp, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Ciudad:</span>
                <?= $form->field($clienteForm, 'ciudad')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->ciudad, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
                <?= Html::img('@web/images/iconos/editar.png', ['class' => 'img-responsive cliente-editar-form', 'id' => 'editar-cliente']);  ?>
            </div>
        </div>
        <div class="row row-formulario-cliente">
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">País</span>
                <?= $form->field($clienteForm, 'paises_id',['template'=>"<div>\n{error}\n{input}\n</div>"])
                    ->widget(Select2::classname(), [
                     'data' => $paises,
                     'language' => 'es',
                     'options' => ['placeholder' => 'Aún no ingresado', 'id' => 'cliente-pais-id', 'value' => $cliente->paises_id , 'class' => 'input-resignup-envio', 'disabled' => true],
                     'pluginOptions' => [
                         'allowClear' => false
                    ],
                    ])->label(false) 
                ?>
            </div>
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Estado</span>
                 <?= $form->field($clienteForm, 'estados_mundo_id')->widget(DepDrop::classname(), [
                    'type' => DepDrop::TYPE_SELECT2,
                    'options' => ['id' => 'cliente-estado-id', 'class' => 'input-resignup-envio', 'value' => $cliente->estados_mundo_id],
                    'pluginOptions' => [
                        'depends' => ['cliente-pais-id'],
                        'initialize' => true,
                        'placeholder' => 'Aún no ingresado',
                        'url' => Url::to(['/checkout/estado'])
                    ]
                ])->label(false);
                ?>
            </div>
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Delegación</span>
                <?= $form->field($clienteForm, 'delegacion')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->delegacion, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
            <div class="col-sm-3 formularios-cliente">
                <span class="label-cliente">Colonia</span>
                <?= $form->field($clienteForm, 'colonia')->textInput(['class' => 'input-resignup-envio', 'value' => $cliente->colonia, 'placeholder' => 'Aún no ingresado', 'disabled' => true])->label(false) ?> 
            </div>
        </div>
        <div class="col-sm-offset-4 col-sm-4 row-actualizar-cliente">
            <?= Html::submitButton('Guardar', ['class' => 'btn-actualizar-cliente', 'disabled' => true]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row row-subtitulo">
        <div class="col-sm-12">
            <p  class="subtitulo-cliente" id="subtitulo-cliente-pedidos">Pedidos <?= Html::img('@web/images/iconos/PlecaP.png', ['id' => 'usuario-desplegar-pedidos']);?><?= Html::img('@web/images/iconos/plecaD.png', ['id' => 'usuario-cerrar-pedidos']); ?></p>
            
                <?= GridView::widget([
                    'id' => 'libros-todos',
                    'dataProvider' => $dataProvider,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    //'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
                    //'summary' => "Mostrando {begin} - {end} de {totalCount} libros",
                    'summary' => "",
                    'emptyText'=>'No se encontraron resultados.',
                    'rowOptions'=>function($model){
                        return [
                            'onclick' => 'location.href="'.Url::to(['cliente/detalle-pedido', 'id' => $model->id ]).'"',
                        ];
                    },
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return ['value' => $model['id']];
                            }
                        ],
                        [
                            'label' => 'Fecha',
                            'attribute' => 'created_at',
                            'contentOptions' => ['class' => 'visualDatos'],
                            'format' => ['date', 'php:d/m/Y']
                        ],
                        [
                            'label' => 'No Pedido',
                            'attribute' => 'numero_pedido',
                            'contentOptions' => ['class' => 'visualDatos']
                        ],
                        [
                            'label' => 'Cantidad',
                            'value' => function($model) { return $model->getCantidad($model->id); ;},
                            'contentOptions' => ['class' => 'visualDatos'],
                        ],
                        [
                            'label' => 'Monto',
                            'attribute' => 'costo_total',
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