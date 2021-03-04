<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Carrusel';
?>
<div class="pad-nav-back"></div>

<section id="libros-tabla">
    <div class="container contenedor" id="mostrar-tablas">
        <div class="row">
            <div class="col-md-12">
                <a href="<?=Url::toRoute(['agregar'])?>" class="btn-anadir-editorial">Añadir</a>

                <?= GridView::widget([
                    'id' => 'entradas-carrusel',
                    'dataProvider' => $dataProvider,
                    'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
                    'summary' => "",
                    'emptyText'=>'No se encontraron resultados.',
                    'columns' => [
                        [
                            'attribute' => 'imagen',
                            'label' => 'Imagen',
                            'value' => function ($model) {
                                return Html::a(Html::img('@web/images/'.$model->imagen), Url::to(['carrusel/ver', 'id' => $model->id]));
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'titulo',
                            'contentOptions'=>function($model){return ['class'=>'visualDatos','id'=>$model->id];},
                            'value' => function ($model) {
                                return Html::a($model->titulo, Url::to(['carrusel/ver', 'id' => $model->id]));
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'status',
                            'label' => 'Activo',
                            'value' => function ($model) {
                                return Html::a($model->status == '1' ? 'Sí' : 'No', Url::to(['carrusel/ver', 'id' => $model->id]));
                            },
                            'format' => 'raw',
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</section>
