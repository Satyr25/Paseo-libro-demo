<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->title = 'Promociones';
?>
<section id="inicio-libros">
    <!-- <div class="alert-placeholder"></div> -->
    <h1 class="titulo-pag">Promociones</h1>

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

<section id="promo-tabla">
    <div class="contenedor" id="mostrar-tablas"> 
            <div class="accionesEB">
                <p id="promo-crear">Crear Promoción</p>
                <p id="promo-eliminar">Eliminar Promoción</p>
            </div>   
            <?php Pjax::begin(['id' => 'pjax-grid-promocion']); ?>
            <?= GridView::widget([
                'id' => 'promocion-todos',
                'dataProvider' => $dataProvider,
                'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                //'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
                //'summary' => "Mostrando {begin} - {end} de {totalCount} libros",
                'summary' => "",
                'emptyText'=>'No se encontraron resultados.',
                'rowOptions'=>function($model){
                    return ['class' => 'view-promo','id'=> $model->id];
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
                        'label' => 'Promoción',
                        'contentOptions' => function($model)
                        {
                            return ['class' => 'visualDatos', 'onclick' => 'detalle('.$model->id.')'];
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{update}',
                        'buttons' => 
                            [
                            'update' => function($url, $model){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javascript:;', ['class' => 'renombrar', 'data-edit' =>$url], 
                                [
                                    'title' => Yii::t('app', 'lead-update'),
                                ]);
                            } 
                        ],
                        'urlCreator' => function ($action, $model, $key, $index) 
                        {
                            if ($action === 'update') {
                                $url = $model->id;
                                return $url;
                            }
                        }
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
