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
                        <h3 class="libro-ver-titulo">Carrusel</h3>
                    </div>
                    <div class="col-md-offset-4 col-md-2">
                        <a href=" <?=Url::toRoute(['editar', 'id' => $carrusel->id]) ?>" class="btn btn-azul">Editar</a>
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>Título</p>
                        <p><?= $carrusel->titulo ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Imagen</p>
                        <p><?= Html::img('@web/images/'.$carrusel->imagen, ['id' => 'preview-carrusel']) ?></p>
                    </div>
                </div>
                <div class="row ver-libro-tabla">
                    <div class="col-md-6">
                        <p>URL</p>
                        <p><?= $carrusel->url ?></p>
                    </div>
                    <div class="col-md-6">
                        <p>Activo</p>
                        <p><?= $carrusel->status == 1 ? 'Sí' : 'No' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
