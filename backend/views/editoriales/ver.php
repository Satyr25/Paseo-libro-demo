<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>

<!--<div class="pad-nav-back"></div>-->
<!--
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
-->

<section>
    <div class="container contenedor" id="libros-ver">
        <div class="row">
            <div class="col-md-12">
                <div class="row row-regresar">
                    <div class="col-md-2">
                        <a href="<?=$ruta?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
                    </div>
                </div>
                <div class="row ver-titulo-row">
                    <div class="col-md-6">
                        <h3 class="libro-ver-titulo">Detalle de Editorial</h3>
                    </div>
                    <div class="col-md-offset-2 col-md-2">   
                        <a href=" <?=Url::toRoute(['ventas/index', 'PedidoSearch[id]' => $editorial->id]) ?>" class="btn btn-azul">Ver ventas</a>
                    </div>
                    <div class="col-md-2">
                        <a href=" <?=Url::toRoute(['actualizar', 'id' => $editorial->id]) ?>" class="btn btn-azul">Editar</a>
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>Clave</p>
                        <p><?= $editorial->clave ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Nombre de Editorial</p>
                        <p><?= ucwords(mb_strtolower($editorial->nombre)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Contacto</p>
                        <p><?= ucwords(mb_strtolower($editorial->contacto)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Teléfono</p>
                        <p><?= $editorial->telefono ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Correo</p>
                        <p> <?= $editorial->correo ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Activo</p>
                        <?php if ($editorial->activo == '1'){ ?> 
                            <p>Si</p>
                        <?php } else { ?> 
                            <p>No</p>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <p>Logo</p>
                        <?= Html::img('@web/images/'.$editorial->logo, ['class' => 'editorial-logo']) ?>
                    </div>
                </div>
                <div class="row ver-titulo-row">
                    <div class="col-md-6">
                        <h3 class="libro-ver-titulo">Detalle de Usuario</h3>
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>Usuario</p>
                        <p><?= $usuario->usuario ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Nombre de Usuario</p>
                        <p><?= ucwords(mb_strtolower($editorial->nombre)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Apellido Paterno</p>
                        <p><?= ucwords(mb_strtolower($usuario->ap_paterno)) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Apellido Materno</p>
                        <p><?= ucwords(mb_strtolower($usuario->ap_materno)) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
