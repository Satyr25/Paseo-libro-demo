<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<section>
    <div class="container contenedor" id="libros-ver">
        <div class="row">
            <div class="col-md-12">
                <div class="row row-regresar">
                    <div class="col-md-2">
                        <a href="<?=Url::toRoute('index')?>" class="link-boton-regresar"> <?= Html::img('@web/images/regresar.png', ['class' => 'img-responsive boton-regresar']) ?> Regresar</a>
                    </div>
                    <div class="col-md-offset-4 col-md-3">
                        
                    </div>
                </div>
                <div class="row ver-titulo-row">
                    <div class="col-md-6">
                        <h3 class="libro-ver-titulo">Detalle de Evento</h3>
                    </div>
                    <div class="col-md-offset-4 col-md-2">
                        <a href=" <?=Url::toRoute(['modificar', 'id' => $evento->id]) ?>" class="btn btn-azul">Editar</a>
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-3">
                        <p>Fecha</p>
                        <p><?= $evento->fecha ?></p>
                    </div>
                    <div class="col-md-3">
                        <p>Hora</p>
                        <p><?= $evento->hora ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Tema</p>
                        <p><?= $evento->tema ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Nombre de evento</p>
                        <p><?= $evento->nombre ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Presentador</p>
                        <p> <?= $evento->presentador ?></p>
                    </div>
                    <div class="col-md-12">
                        <p>Imagen</p>
                        <?= Html::img('@web/images/'.$evento->imagen, ['class' => 'evento-img']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
